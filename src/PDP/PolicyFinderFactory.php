<?php
declare(strict_types = 1);

namespace Cerberus\PDP;

use Cerberus\PDP\Policy\CombiningAlgorithmFactory;
use Cerberus\PDP\Exception\PolicyFinderException;
use Cerberus\PDP\Policy\Factory\ConditionFactory;
use Cerberus\PDP\Policy\Factory\PolicyElement\PolicyFactory;
use Cerberus\PDP\Policy\Factory\VariableDefinitionFactory;
use Cerberus\PDP\Policy\PolicyDef;
use Cerberus\PDP\Policy\PolicyFinder;
use Cerberus\PDP\Utility\Properties;
use Ds\Set;

class PolicyFinderFactory
{
    protected $needsInit = true;
    /** @var PolicyDef[]|Set */
    protected $referencedPolicies;

    /** @var PolicyDef[]|Set */
    protected $rootPolicies;

    public function __construct()
    {
        $this->combiningAlgorithmFactory = new CombiningAlgorithmFactory();
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
                $policy = PolicyFactory::create($policyData[$policyType]);
                $policies->add($policy);
                break;
            case 'policySet':
            default:
                throw new PolicyFinderException("Only 'policy' and 'policySet' are permitted top level keys");
        }

        return $policies;
    }
}
