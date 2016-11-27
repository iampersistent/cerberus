<?php
declare(strict_types = 1);

namespace Cerberus\PDP;

use Cerberus\PDP\Policy\Expressions\PolicyFinderException;
use Cerberus\PDP\Policy\Policy;
use Cerberus\PDP\Policy\PolicyDef;
use Cerberus\PDP\Policy\PolicyFinder;
use Cerberus\PDP\Utility\Properties;
use Ds\Set;
use Exception;

class PolicyFinderFactory
{
    protected $needsInit = true;
    /** @var PolicyDef[]|Set */
    protected $referencedPolicies;

    /** @var PolicyDef[]|Set */
    protected $rootPolicies;

    public function __construct()
    {
        $this->referencedPolicies = new Set();
        $this->rootPolicies = new Set();
    }

    public function getPolicyFinder(Properties $properties): PolicyFinder
    {
        $this->init($properties);

        return new PolicyFinder($this->rootPolicies, $this->referencedPolicies);
    }

    protected function handlePolicies($policyData): Set
    {
        $policies = new Set();
        $policyType = key($policyData);
        switch ($policyType) {
            case 'policy':
                $policies->add($this->createPolicy($policyData[$policyType]));
                break;
            case 'policySet':
            default:
                throw new PolicyFinderException("Only 'policy' and 'policySet' are permitted top level keys");
        }

        return $policies;
    }

    protected function createPolicy(array $policyData): Policy
    {
        $policy = new Policy();
        foreach ($policyData as $policyName => $data) {
            try {
                $processMethod = 'process' . ucfirst($policyName);
                $this->$processMethod($policy, $data);
            } catch (Exception $e) {
                if (method_exists($this, $processMethod)) {
                    $message = 'There was a problem processing ' . $policyName;
                } else {
                    $message = 'Invalid policy ' . $policyName;
                }
                throw new PolicyFinderException($message);
            }
        }

        return $policy;
    }

    protected function processRules($policy, $data)
    {

    }

    protected function processPolicyId(PolicyDef $policy, $data)
    {
        $policy->setIdentifier($data);
    }

    protected function processRuleCombiningAlgorithmId($policy, $data)
    {

    }
}