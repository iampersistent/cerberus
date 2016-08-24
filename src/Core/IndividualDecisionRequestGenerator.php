<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Collection;

class IndividualDecisionRequestGenerator
{
    protected $individualDecisionRequests;

    public function __construct()
    {
        $this->individualDecisionRequests = new ;
    }

    /**
     * Populates the individual decision <code>Request</code>s from the given <code>Request</code> using all
     * supported profiles. The process here is documented as step 1. of Section 4 of the XACML document.
     *
     * @param request the <code>Request</code> to explode
     */
    protected function createIndividualDecisionRequests(Request $request)
    {
        /*
         * If the request is bad to begin with, just add it to the list and be done.
         */
        if ($request->getStatus() != null && !$request->getStatus()->isOk()) {
            $this->individualDecisionRequests->add($request);

            return;
        }

        /*
         * Check to see if this Request is a MultiRequest
         */
        $requestReferences = $request->getMultiRequests();
        if ($requestReferences != null && $requestReferences->next()) {
            while ($requestReferences->hasNext()) {
                $requestFromReferences = $this->processMultiRequest($request, $requestReferences->next());
                if ($requestFromReferences->getStatus() == null || $requestFromReferences->getStatus()->isOk()) {
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
        $requestAttributes = $request->getRequestAttributes()->getIterator();
        if ($requestAttributes == null || !$requestAttributes->hasNext()) {
            /*
             * There are no attributes to process anyway. The PDP will give an indeterminate result from this
             */
            $this->individualDecisionRequests->add($request);

            return;
        }

        /*
         * We need to do a quick check for multiple Attributes with the same Category
         */
        $containsMultiples = false;

        Set<Identifier> setCategories = new HashSet<Identifier>();
        while (iterRequestAttributes.hasNext() && !$containsMultiples) {
            RequestAttributes requestAttributes = iterRequestAttributes.next();
            Identifier identifierCategory = requestAttributes.getCategory();
            if (identifierCategory == null) {
                $this->individualDecisionRequests.add(new StdMutableRequest(STATUS_NO_CATEGORY));
                return;
            }
            if (setCategories.contains(identifierCategory)) {
                $containsMultiples = true;
            } else {
                setCategories.add(identifierCategory);
            }
        }

        /*
         * If there are no instances of categories with multiple Attributes elements, then no splitting is
         * done here, just move on to the next check.
         */
        if (!$containsMultiples) {
            $this->processScopes($request);
        } else {
            iterRequestAttributes = $request->getRequestAttributes().iterator();
            Map<Identifier, List<RequestAttributes>> mapCategories = new HashMap<Identifier, List<RequestAttributes>>();
            while (iterRequestAttributes.hasNext()) {
                RequestAttributes requestAttributes = iterRequestAttributes.next();
                Identifier identifierCategory = requestAttributes.getCategory();
                List<RequestAttributes> listRequestAttributes = mapCategories.get(identifierCategory);
                if (listRequestAttributes == null) {
                    listRequestAttributes = new ArrayList<RequestAttributes>();
                    mapCategories.put(identifierCategory, listRequestAttributes);
                }
                listRequestAttributes.add(requestAttributes);
            }

            StdMutableRequest requestRoot = new StdMutableRequest();
            requestRoot.setRequestDefaults($request->getRequestDefaults());
            requestRoot.setReturnPolicyIdList($request->getReturnPolicyIdList());
            $this->explodeOnCategory(mapCategories.keySet().toArray(idArray), 0, requestRoot, mapCategories);
        }
    }
}