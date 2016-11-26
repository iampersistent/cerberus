<?php
declare(strict_types = 1);

namespace Cerberus\PDP;

use Cerberus\PDP\Policy\Expressions\PolicyFinderException;
use Cerberus\PDP\Policy\Policy;
use Cerberus\PDP\Policy\PolicyDef;
use Ds\Set;
use Exception;

class ArrayPolicyFinderFactory extends PolicyFinderFactory
{
    protected function init($properties)
    {
        $this->rootPolicies = $this->handleProperties($properties);
        $this->needsInit = false;
    }

    protected function handleProperties($properties): Set
    {
        $policies = new Set();
        $policyType = key($properties);
        switch ($policyType) {
            case 'policy':
                $policies->add($this->createPolicy($properties[$policyType]));
                break;
            case 'policySet':
            default:
                throw new PolicyFinderException("Only 'policy' and 'policySet' are permitted top level keys");
        }

        return $policies;
    }

    protected function createPolicy(array $properties): Policy
    {
        $policy = new Policy();
        foreach ($properties as $property => $data) {
            try {
                $processMethod = 'process' . ucfirst($property);
                $this->$processMethod($policy, $data);
            } catch (Exception $e) {
                if (method_exists($this, $processMethod)) {
                    $message = 'There was a problem processing ' . $property;
                } else {
                    $message = 'Invalid property ' . $property;
                }
                throw new PolicyFinderException($message);
            }
        }

        return $policy;
    }

    protected function processPolicyId(PolicyDef $policy, $data)
    {
        $policy->setIdentifier($data);
    }

    protected function processRuleCombiningAlgorithmId($policy, $data)
    {

    }

    protected function processRules($policy, $data)
    {

    }
}