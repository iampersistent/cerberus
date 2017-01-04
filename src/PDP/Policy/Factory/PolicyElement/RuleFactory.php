<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Exception\FactoryException;
use Cerberus\PDP\Policy\Policy;
use Cerberus\PDP\Policy\Rule;
use Cerberus\PDP\Policy\RuleEffect;

class RuleFactory extends PolicyElementFactory
{
    /**
     * @param Policy $policy
     * @param array  $data
     *
     * @return Rule|PolicyElement
     */
    public static function create(Policy $policy, array $data): PolicyElement
    {
        $rule = new Rule($policy);
        self::processIncomingData($policy, $rule, $data);

        return $rule;
    }

    protected static function processAdviceExpressions(Policy $policy, Rule $rule, $data)
    {
        throw new FactoryException('processAdviceExpressions in RuleFactory needs to processed');
    }

    protected static function processCondition(Policy $policy, Rule $rule, $data)
    {
        $condition = ConditionFactory::create($policy, $data);
        $rule->setCondition($condition);
    }

    protected static function processEffect(Policy $policy, Rule $rule, $data)
    {
        $rule->setRuleEffect(RuleEffect::getRuleEffect($data));
    }

    protected static function processRuleId(Policy $policy, Rule $rule, $data)
    {
        $rule->setRuleId($data);
    }

    protected static function processObligationExpressions(Policy $policy, Rule $rule, $data)
    {
        throw new FactoryException('processObligationExpressions in RuleFactory needs to processed');
    }

    protected static function processTarget(Policy $policy, Rule $rule, $data)
    {
        $target = TargetFactory::create($policy, $data);
        $rule->setTarget($target);
    }
}
