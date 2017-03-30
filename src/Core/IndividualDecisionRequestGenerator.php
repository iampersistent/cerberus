<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Exception;
use Ds\Map;
use Ds\Set;
use Cerberus\Core\Enums\AttributeCategoryIdentifier;
use Cerberus\Core\Enums\MultipleIdentifier;
use Cerberus\Core\Exception\ScopeResolverException;
use Cerberus\PDP\ScopeResolver;

class IndividualDecisionRequestGenerator
{
    protected $idArray;

    protected $individualDecisionRequests;

    /** @var Request */
    protected $originalRequest;

    /** @var ScopeResolver */
    protected $scopeResolver;

    public function __construct(ScopeResolver $scopeResolver, Request $request)
    {
        $this->individualDecisionRequests = new Set();
        $this->originalRequest = $request;
        $this->scopeResolver = $scopeResolver;
        $this->createIndividualDecisionRequests($request);
    }

    public function getIndividualDecisionRequests(): Set
    {
        return $this->individualDecisionRequests;
    }

    /**
     * Populates the individual decision Request from the given Request using all
     * supported profiles. The process here is documented as step 1. of Section 4 of the XACML document.
     *
     * @param Request $request
     */
    protected function createIndividualDecisionRequests(Request $request)
    {
        /*
         * If the request is bad to begin with, just add it to the list and be done.
         */
        if ($request->getStatus() && ! $request->getStatus()->isOk()) {
            $this->individualDecisionRequests->add($request);

            return;
        }

        /*
         * Check to see if this Request is a MultiRequest
         */
        if ($requestReferences = $request->getMultiRequests()) {
            foreach ($requestReferences as $requestReference) {
                $requestFromReferences = $this->processMultiRequest($request, $requestReference);
                if (! $requestFromReferences->getStatus() || $requestFromReferences->getStatus()->isOk()) {
                    $this->processRepeatedCategories($requestFromReferences);
                } else {
                    /*
                     * Just add the bad request to the list. It will be cause a Result with the same bad
                     * status when the PDP actually runs the $request->
                     */
                    $this->individualDecisionRequests->add($requestFromReferences);
                }
            }
        } else {
            $this->processRepeatedCategories($request);
        }
    }

    /**
     * Checks to see if the given <code>Request</code> contains instances of repeated categories in the
     * request attributes elements.
     *
     * @param request the <code>Request</code> to check
     */
    protected function processRepeatedCategories(Request $request)
    {
        if ($request->getRequestAttributes()->isEmpty()) {
            /*
             * There are no attributes to process anyway. The PDP will give an indeterminate result from this
             */
            $this->individualDecisionRequests->add($request);

            return;
        }

        /*
         * We need to do a skip out for multiple Attributes with the same Category
         */
        $containsMultiples = false;

        $setCategories = new Set();
        foreach ($request->getRequestAttributes() as $requestAttribute) {
            $identifierCategory = $requestAttribute->getCategory();
            if ($identifierCategory == null) {
                $this->individualDecisionRequests->add(new Request(Status::createSyntaxError()));

                return;
            }
            if ($setCategories->contains($identifierCategory)) {
                $containsMultiples = true;
                break;
            }
            $setCategories->add($identifierCategory);
        }

        /*
         * If there are no instances of categories with multiple Attributes elements, then no splitting is
         * done here, just move on to the next check.
         */
        if (! $containsMultiples) {
            $this->processScopes($request);
        } else {
            $requestAttributes = $request->getRequestAttributes()->iterator();
            $mapCategories = new Map();
            foreach ($requestAttributes as $requestAttribute) {
                $identifierCategory = $requestAttribute->getCategory();
                $listRequestAttributes = $mapCategories->get($identifierCategory);
                if ($listRequestAttributes == null) {
                    $listRequestAttributes = new Set();
                    $mapCategories->put($identifierCategory, $listRequestAttributes);
                }
                $listRequestAttributes->add($requestAttributes);
            }

            $requestRoot = new Request();
            $requestRoot->setRequestDefaults($request->getRequestDefaults());
            $requestRoot->setReturnPolicyIdList($request->shouldReturnPolicyIdList());
            $this->explodeOnCategory($mapCategories->keySet()->toArray($this->idArray), 0, $requestRoot,
                $mapCategories);
        }
    }

    /**
     * Checks to see if there are any categories that include an attribute with a "scope" identifier. If so,
     * the scopes are expanded and individual decision $requests are generated with the expanded scopes.
     *
     * @param $request
     */
    protected function processScopes(Request $request)
    {
        if ($request->getStatus() && !$request->getStatus()->isOk()) {
            throw new Exception();
        }

        /*
         * If there is no scope resolver, then just move on to the content selectors
         */
        //if ($this->scopeResolver-> == null) {
            $this->processContentSelectors($request);

            return;
        //}
// todo
        /*
         * Scope only applies to the resource category, so just get the RequestAttributes for that. At this
         * point there should be at most one.
         */
        $requestAttributesResource = $request->getRequestAttributes(AttributeCategoryIdentifier::RESOURCE);
        if (! $requestAttributesResource) {
            $this->processContentSelectors($request);

            return;
        }
        /*
         * Get the $requested scope
         */
         $scopeQualifier = null; // ScopeQualifier
        try {
            $scopeQualifier = $this->getScopeQualifier($requestAttributesResource);
        } catch (ScopeResolverException $e) {
            $this->individualDecisionRequests
                ->add(new Request(Status::createSyntaxError($e->getMessage())));

            return;
        }
        if (! $scopeQualifier) {
            $this->processContentSelectors($request);

            return;
        }

        /*
         * Get the resource-id attributes and iterate over them, generating individual resource id values
         * using the scope resolver.
         */
        $attributesResourceId = $requestAttributesResource->getAttributes(ID_RESOURCE_RESOURCE_ID);
        if (! $attributesResourceId) {
            $this->individualDecisionRequests->add(new Request(Status::createSyntaxError()));

            return;
        }

        /*
         * Make a copy of the $request attributes with the scope and resource ID values removed.
         */
         $requestAttributesBase = $this->removeScopeAttributes($requestAttributesResource); // RequestAttributes

        /*
         * Set up the basic Request to match the input $request but with no resource attributes
         */
        $request = $this->removeResources($request);
/*
        $atLeastOne = false;
        while (iterAttributesResourceId . hasNext()) {
            Attribute $attributeResourceId = iterAttributesResourceId . next();
            ScopeResolverResult $scopeResolverResult = null;
            try {
                $scopeResolverResult = $this->scopeResolver->resolveScope($attributeResourceId, $scopeQualifier);
            } catch (ScopeResolverException $e) {
                $this->logger . error("ScopeResolverException resolving " + $attributeResourceId->toString() + ": "
                    + ex . getMessage(), ex);
            }
            if ($scopeResolverResult->getStatus() != null && ! $scopeResolverResult->getStatus() . isOk()) {
                $this->individualDecisionRequests->add(new MutableRequest($scopeResolverResult->getStatus()));

                return;
            }
            Iterator < Attribute> iterAttributesResourceIdResolved = $scopeResolverResult->getAttributes();
            if (iterAttributesResourceIdResolved != null) {
                while (iterAttributesResourceIdResolved . hasNext()) {
                    MutableRequestAttributes $requestAttributes = new MutableRequestAttributes(
                        $requestAttributesBase);
                    $requestAttributes->add(iterAttributesResourceIdResolved . next());
                    MutableRequest $requestExploded = new MutableRequest($request);
                    $requestExploded->add($requestAttributes);
                    $this->processContentSelectors($requestExploded);
                    $atLeastOne = true;
                }
            }
        }
        if (! $atLeastOne) {
            $this->logger . warn("No scopes expanded.  Using original resource ids");
            iterAttributesResourceId = $requestAttributesResource
                . getAttributes(XACML3 . ID_RESOURCE_RESOURCE_ID);
            assert iterAttributesResourceId != null;
            while (iterAttributesResourceId . hasNext()) {
                $requestAttributesBase->add(iterAttributesResourceId . next());
            }
            $request->add($requestAttributesBase);
            $this->processContentSelectors($request);
        }
*/
    }

    protected function processContentSelectors(Request $request)
    {
        $requestAttributes = $request->getRequestAttributes();
        if ($requestAttributes->isEmpty()) {
            $this->individualDecisionRequests->add($request);

            return;
        }

        /*
         * Quick check for any categories with a multiple:content-selector attribute
         */
        $hasMultipleContentSelectors = false;
        foreach ($requestAttributes as $attribute) {
            if ($hasMultipleContentSelectors = $attribute->hasAttribute(MultipleIdentifier::CONTENT_SELECTOR)) {
                $hasMultipleContentSelectors = true;

                break;
            }
        }

        /*
         * Iterate over all of the categories and see if there are any attributes in them with a
         * multiple:content-selector
         */
        if (!$hasMultipleContentSelectors) {
            $this->individualDecisionRequests->add($request);
        } else {
            $listRequestAttributes = new Set();
            $listRequestAttributes->add($request->getRequestAttributes());

            $requestInProgress = new Request();
            $requestInProgress->setRequestDefaults($request->getRequestDefaults());
            $requestInProgress->setReturnPolicyIdList($request->shouldReturnPolicyIdList());
            $this->explodeOnContentSelector($listRequestAttributes, 0, $requestInProgress);
        }
    }
}