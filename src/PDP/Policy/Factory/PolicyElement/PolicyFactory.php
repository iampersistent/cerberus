<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Exception\PolicyFinderException;
use Cerberus\PDP\Policy\Factory\CombinerFactory;
use Cerberus\PDP\Policy\Policy;
use Cerberus\PDP\Policy\Target;
use Exception;

class PolicyFactory
{
    public static function create(array $policyData): Policy
    {
        $policy = (new Policy())
            ->setTarget(new Target());

        foreach ($policyData as $elementName => $data) {
            $elementName = ucfirst($elementName);
            $processMethod = "process$elementName";
            $factoryClass = "Cerberus\\PDP\\Policy\\Factory\\" . $elementName . 'Factory';
            if (method_exists(self::class, $processMethod)) {
                try {
                    self::$processMethod($policy, $data);
                } catch (Exception $e) {
                    throw new PolicyFinderException("There was a problem processing $elementName");
                }
            } else {
                try {
                    $factoryClass::create($policy, $data);
                } catch (Exception $e) {
                    if (class_exists($factoryClass)) {
                        throw new PolicyFinderException(
                            "The factory for $elementName needs to be implemented, or $elementName is not valid"
                        );
                    } else {
                        throw new PolicyFinderException("Invalid policy $elementName");
                    }
                }
            }
        }

        return $policy;
    }

    protected function processRules(Policy $policy, $data)
    {
        foreach ($data as $ruleData) {
            $rule = RuleFactory::create($policy, $ruleData);
            $policy->addRule($rule);
        }
    }

    protected function processPolicyId(Policy $policy, $data)
    {
        $policy->setIdentifier($data);
    }

    protected function processRuleCombiningAlgorithmId(Policy $policy, $data)
    {
        $combiner = CombinerFactory::create($data);
        $policy->setRuleCombiningAlgorithm($combiner);
    }

    protected function processVariableDefinitions(Policy $policy, $data)
    {
        foreach ($data as $variableDefinitionData) {
            $variableDefinition = VariableDefinitionFactory::create($policy, $variableDefinitionData);

            $policy->addVariableDefinition($variableDefinition);
        }
    }
}
