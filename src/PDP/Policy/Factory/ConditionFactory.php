<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory;

use Cerberus\PDP\Policy\Condition;
use Cerberus\PDP\Policy\Policy;

class ConditionFactory
{
    public static function create(Policy $policy, $data): Condition
    {
        $condition = new Condition();
        if (isset($data['apply'])) {
            $apply = ApplyFactory::create($data['apply']);
            $condition->setExpression($apply);
        }
        if (isset($data['variableReference'])) {
            $variableReference = VariableReferenceFactory::create($policy, $data['variableReference']);
            $condition->setExpression($variableReference);
        }

        return $condition;
    }
}