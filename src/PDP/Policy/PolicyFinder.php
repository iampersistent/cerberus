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

    public function __construct($rootPolicyDefs, $referencedPolicyDefs = null)
    {
        $this->rootsList = new Set();
        $this->policiesMap = new Map();

        $addPolicy = function($rootPolicyDef) {
            $this->rootsList->add($rootPolicyDef);
            $this->updatePolicyMap($rootPolicyDef);
        };

        if ($rootPolicyDefs instanceof PolicyDef) {
            $addPolicy($rootPolicyDefs);
        } else {
            foreach ($rootPolicyDefs as $rootPolicyDef) {
                $addPolicy($rootPolicyDef);
            }
        }

        if ($referencedPolicyDefs) {
            foreach ($referencedPolicyDefs as $policyDef) {
                $this->storeInPolicyMap($policyDef);
            }
        }
    }

    public function getPolicy($idReferenceMatch): Policy
    {
        $result = $this->lookupPolicyByIdentifier($idReferenceMatch);

        if ($result->getStatus()->isOk()) {
            return $result->getPolicyDef();
        }

        throw new PolicyFinderException($result->getStatus()->getStatusMessage());
    }

    public function getPolicySet($idReferenceMatch): PolicySet
    {
        $result = $this->lookupPolicySetByIdentifier($idReferenceMatch);

        return $result->getPolicyDef();
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
                switch ($matchResult->getMatchCode()->getValue()) {
                    case MatchCode::INDETERMINATE:
                        if (! $firstIndeterminate) {
                            $firstIndeterminate = new PolicyFinderResult($matchResult->getStatus());
                        }
                        break;
                    case MatchCode::MATCH:
                        if (! $policyDefFirstMatch) {
                            $policyDefFirstMatch = $policyDef;
                        } else {
                            return new PolicyFinderResult(
                                new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), 'Multiple applicable root policies')
                            );
                        }
                        break;
                    case MatchCode::NO_MATCH:
                        break;
                }
            } catch (EvaluationException $e) {
                if (! $firstIndeterminate) {
                    $firstIndeterminate = new PolicyFinderResult(
                        new Status(
                            StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                            $e->getMessage()));
                }
            }
        }

        if (! $policyDefFirstMatch) {
            if (! $firstIndeterminate) {
                return $firstIndeterminate;
            } else {
                return new PolicyFinderResult(
                    new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), 'No matching root policy found')
                );
            }
        }

        return new PolicyFinderResult(
            new Status(StatusCode::STATUS_CODE_OK()),
            $policyDefFirstMatch
        );
    }

    protected function storeInPolicyMap(PolicyDef $policyDef)
    {
        $policyId = $policyDef->getIdentifier();
        if (null === $listPolicyDefs = $this->policiesMap->get($policyId, null)) {
            $listPolicyDefs = new Set();
            $this->policiesMap->put($policyId, $listPolicyDefs);
        }
        $listPolicyDefs->add($policyDef);
    }

    protected function getFromPolicyMap($idReferenceMatch, $classPolicyDef)
    {
        /*
         * Get all of the PolicyDefs for the Identifier in the reference $match
         */
        return $this->policiesMap->get($idReferenceMatch, null);

        /*
         * todo: if versioning gets implemented, Iterate over all of the PolicyDefs that were found and select only the ones that $match the version
         * request and the isPolicySet
         */
    }

    /**
     * Looks up the given {@link org.apache.openaz.xacml.api.Identifier} in the map first. If not found, and
     * the <code>Identifier</code> contains a URL, then attempts to retrieve the document from the URL and
     * caches it.
     *
     * @param $idReferenceMatch the <code>IdReferenceMatch</code> to look up
     */
    protected function lookupPolicyByIdentifier($id): PolicyFinderResult
    {
        $listCachedPolicies = $this->getFromPolicyMap($id, Policy::class);

        if ($listCachedPolicies) {
            return new PolicyFinderResult(
                Status::createOk(),
                $this->getBestMatch($listCachedPolicies)
            );
        } else {
            return new PolicyFinderResult(
                Status::createProcessingError("No matching policy found")
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

    protected function getBestMatch(Set $matches): PolicyDef
    {
        switch ($matches->count()) {
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