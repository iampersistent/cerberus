<?php
declare(strict_types = 1);

namespace Cerberus\PDP;

use Cerberus\PDP\Policy\AnyOf;
use Cerberus\PDP\Policy\CombiningAlgorithmFactory;
use Cerberus\PDP\Policy\Condition;
use Cerberus\PDP\Policy\Expressions\PolicyFinderException;
use Cerberus\PDP\Policy\Policy;
use Cerberus\PDP\Policy\PolicyDef;
use Cerberus\PDP\Policy\PolicyFinder;
use Cerberus\PDP\Policy\Rule;
use Cerberus\PDP\Policy\RuleEffect;
use Cerberus\PDP\Policy\Target;
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
        $policy = (new Policy())
            ->setTarget(new Target());

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

    protected function processRules(PolicyDef $policy, $data)
    {
        foreach ($data as $ruleData) {
            $target = new Target();
            foreach ($ruleData['target']['anyOf'] as $anyOfData) {
                $anyOf = new AnyOf($anyOfData);
                $target->addAnyOf($anyOf);
            }
            $rule = (new Rule($policy))
                ->setCondition(new Condition($ruleData['condition']))
                ->setRuleEffect(RuleEffect::get(strtoupper($ruleData['effect'])))
                ->setRuleId($ruleData['ruleId'])
                ->setTarget($target);

            $policy->addRule($rule);
        }
    }

    protected function processPolicyId(PolicyDef $policy, $data)
    {
        $policy->setIdentifier($data);
    }

    protected function processRuleCombiningAlgorithmId(PolicyDef $policy, $data)
    {
        // todo: move into factory
        $parts = explode(':', $data);
        $identifier = array_pop($parts);
        $combinerClass = '\\Cerberus\\PDP\\Combiner\\' . str_replace('-', '', ucwords($identifier, '-'));
        $combiner = new $combinerClass($identifier);

        $policy->setRuleCombiningAlgorithm($combiner);
    }
}