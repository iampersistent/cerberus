<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Policy\Condition;
use Cerberus\PDP\Policy\Policy;

class ConditionFactory extends PolicyElementFactory
{
    /**
     * @param Policy $policy
     * @param array  $data
     *
     * @return PolicyElement|Condition
     */
    public static function create(Policy $policy, array $data): PolicyElement
    {
        $condition = new Condition();
        if (isset($data['apply'])) {
            $apply = ApplyFactory::create($policy, $data['apply']);
            $condition->setExpression($apply);
        }
        if (isset($data['variableReference'])) {
            $variableReference = VariableReferenceFactory::create($policy, $data['variableReference']);
            $condition->setExpression($variableReference);
        }

        return $condition;
    }
}
