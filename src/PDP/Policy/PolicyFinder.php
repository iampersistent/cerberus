<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\MatchCode;
use Cerberus\PDP\Exception\EvaluationException;
use Cerberus\PDP\Policy\Expressions\PolicyFinderException;
use Ds\Map;
use Ds\Set;

class PolicyFinder
{
    protected $rootsList;
    protected $policiesMap;

    public function __construct(PolicyDef $rootPolicyDef, $referencedPolicyDefs)
    {
        $this->rootsList = new Set();
        $this->policiesMap = new Map();

        if ($rootPolicyDef) {
            $this->rootsList->add($rootPolicyDef);
            $this->updatePolicyMap($rootPolicyDef);
        }

        if ($referencedPolicyDefs) {
            foreach ($referencedPolicyDefs as $policyDef) {
                $this->storeInPolicyMap($policyDef);
            }
        }
    }

    public function getPolicy($idReferenceMatch): Policy
    {
        return $this->lookupPolicyByIdentifier($idReferenceMatch);
    }

    public function getPolicySet($idReferenceMatch): PolicySet
    {
        return $this->lookupPolicySetByIdentifier($idReferenceMatch);
    }

    public function addReferencedPolicy(PolicyDef $policyDef)
    {
        $this->updatePolicyMap($policyDef);
    }

    public function getRootPolicyDef(EvaluationContext $evaluationContext): PolicyFinderResult
    {
        $policyDefFirstMatch = null;
        $firstIndeterminate = null;
        foreach ($this->rootsList as $policyDef) {
            $matchResult = null;
            try {
                $matchResult = $policyDef->match($evaluationContext);
                switch ($matchResult->getMatchCode()) {
                    case MatchCode::INDETERMINATE:
                        if ($firstIndeterminate == null) {
                            $firstIndeterminate = new PolicyFinderResult($matchResult->getStatus());
                        }
                        break;
                    case MatchCode::MATCH:
                        if ($policyDefFirstMatch == null) {
                            $policyDefFirstMatch = $policyDef;
                        } else {
                            return new PolicyFinderResult(
                                new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), "Multiple applicable root policies")
                            );
                        }
                        break;
                    case MatchCode::NO_MATCH:
                        break;
                }
            } catch (EvaluationException $e) {
                if ($firstIndeterminate == null) {
                    $firstIndeterminate = new PolicyFinderResult(
                        new Status(
                            StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                            $e->getMessage()));
                }
            }
        }

        if ($policyDefFirstMatch == null) {
            if ($firstIndeterminate != null) {
                return $firstIndeterminate;
            } else {
                return new PolicyFinderResult(
                    new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), "No matching root policy found")
                );
            }
        } else {
            return new PolicyFinderResult(
                new Status(StatusCode::STATUS_CODE_OK()),
                $policyDefFirstMatch
            );
        }
    }

    protected function storeInPolicyMap(PolicyDef $policyDef)
    {
        $listPolicyDefs = $this->policiesMap->get($policyDef->getIdentifier());
        if ($listPolicyDefs == null) {
            $listPolicyDefs = new Set();
            $this->policiesMap->put($policyDef->getIdentifier(), $listPolicyDefs);
        }
        $listPolicyDefs->add($policyDef);
    }

    protected function getFromPolicyMap($idReferenceMatch, $classPolicyDef)
    {
        /*
         * Get all of the PolicyDefs for the Identifier in the reference $match
         */
        $listPolicyDefForId = $this->policiesMap->get($idReferenceMatch->getId());
        if ($listPolicyDefForId == null) {
            return null;
        }

        /*
         * Iterate over all of the PolicyDefs that were found and select only the ones that $match the version
         * request and the isPolicySet
         */
        $listPolicyDefMatches = null;
        foreach ($listPolicyDefForId as $policyDef) {
            if ($classPolicyDef->isInstance($policyDef) && $policyDef->matches($idReferenceMatch)) {
                if ($listPolicyDefMatches == null) {
                    $listPolicyDefMatches = new Set();
                }
                $listPolicyDefMatches->add($classPolicyDef->cast($policyDef));
            }
        }

        return $listPolicyDefMatches;
    }

    /**
     * Looks up the given {@link org.apache.openaz.xacml.api.Identifier} in the map first. If not found, and
     * the <code>Identifier</code> contains a URL, then attempts to retrieve the document from the URL and
     * caches it.
     *
     * @param $idReferenceMatch the <code>IdReferenceMatch</code> to look up
     *
     * @return a <code>PolicyFinderResult</code> with the requested <code>Policy</code> or an error status
     */
    protected function lookupPolicyByIdentifier($idReferenceMatch): PolicyFinderResult
    {
        $listCachedPolicies = $this->getFromPolicyMap($idReferenceMatch, Policy::class);
        if ($listCachedPolicies == null) {
            $id = $idReferenceMatch->getId();
            if ($id != null) {
                $uri = $id->getUri();
                if ($uri != null && $uri->isAbsolute()) {
                    $policyDef = null;
                    try {
                        $policyDef = $this->loadPolicyDefFromURI($uri);
                    } catch (PolicyFinderException $e) {
                        return new PolicyFinderResult(
                            new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), $e->getMessage())
                        );
                    }
                    if ($policyDef != null) {
                        if ($policyDef instanceof Policy) {
                            $listPolicyDefs = new Set();
                            $listPolicyDefs->add($policyDef);
                            $this->policiesMap->put($id, $listPolicyDefs);
                            $this->policiesMap->put($policyDef->getIdentifier(), $listPolicyDefs);

                            return new PolicyFinderResult(
                                new Status(StatusCode::STATUS_CODE_OK(), "No matching policy found"),
                                $policyDef
                            );
                        } else {
                            return new PolicyFinderResult(
                                new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), "Not a policy")
                            );
                        }
                    } else {
                        return new PolicyFinderResult(
                            new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), "No matching policy found")
                        );
                    }
                }
            }
        }
        if ($listCachedPolicies != null) {
            return new PolicyFinderResult($this->getBestMatch($listCachedPolicies));
        } else {
            return new PolicyFinderResult(
                new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), "No matching policy found")
            );
        }
    }

    /**
     * Looks up the given {@link org.apache.openaz.xacml.api.Identifier} in the map first. If not found, and
     * the <code>Identifier</code> contains a URL, then attempts to retrieve the document from the URL and
     * caches it.
     *
     * @param $idReferenceMatch the <code>IdReferenceMatch</code> to look up
     *
     * @return a <code>PolicyFinderResult</code> with the requested <code>PolicySet</code> or an error status
     */
    protected function lookupPolicySetByIdentifier($idReferenceMatch): PolicyFinderResult
    {
        $listCachedPolicySets = $this->getFromPolicyMap($idReferenceMatch, PolicySet::class);
        if ($listCachedPolicySets == null) {
            $id = $idReferenceMatch->getId();
            if ($id != null) {
                $uri = $id->getUri();
                if ($uri != null && $uri->isAbsolute()) {
                    $policyDef = null;
                    try {
                        $policyDef = $this->loadPolicyDefFromURI($uri);
                    } catch (PolicyFinderException $e) {
                        return new PolicyFinderResult(
                            new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), $e->getMessage())
                        );
                    }
                    if ($policyDef) {
                        if ($policyDef instanceof PolicySet) {
                            $listPolicyDefs = new Set();
                            $listPolicyDefs->add($policyDef);
                            $this->policiesMap->put($id, $listPolicyDefs);
                            $this->policiesMap->put($policyDef->getIdentifier(), $listPolicyDefs);

                            return new PolicyFinderResult(
                                new Status(StatusCode::STATUS_CODE_OK()),
                                $policyDef
                            );
                        } else {
                            return new PolicyFinderResult(
                                new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), "Not a policy set")
                            );
                        }
                    } else {
                        return new PolicyFinderResult(
                            new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), "No matching policy set found")
                        );
                    }
                }
            }
        }
        if ($listCachedPolicySets != null) {
            return new PolicyFinderResult($this->getBestMatch($listCachedPolicySets));
        } else {
            return new PolicyFinderResult(
                new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), "No matching policy set found")
            );
        }
    }


    protected function getBestMatchN($matches): PolicyDef
    {
        $bestMatch = null;
        $bestVersion = null;
        foreach ($matches as $match) {
            if ($bestMatch == null) {
                $bestMatch = $match;
                $bestVersion = $match->getVersion();
            } else {
                 $matchVersion = $match->getVersion();
                if ($matchVersion != null && $matchVersion->compareTo($bestVersion) > 0) {
                    $bestMatch = $match;
                    $bestVersion = $matchVersion;
                }
            }
        }

        return $bestMatch;
    }

    protected function getBestMatch($matches): PolicyDef
    {
        switch ($matches->size()) {
            case 0:
                return null;
            case 1:
                return $matches->get(0);
            default:
                return $this->getBestMatchN($matches);
        }
    }

    /**
     * Adds the given <code>PolicyDef</code> to the map of loaded <code>PolicyDef</code>s and adds its child
     * <code>PolicyDef</code>s recursively.
     *
     * @param policyDef the <code>PolicyDef</code> to add
     */
    protected function updatePolicyMap(PolicyDef $policyDef)
    {
        $this->storeInPolicyMap($policyDef);
        if ($policyDef instanceof PolicySet) {
            foreach ($policyDef->getChildren() as $policySetChild) {
                if ($policySetChild instanceof PolicyDef) {
                    $this->updatePolicyMap($policySetChild);
                }
            }
        }
    }
}