<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Policy\Policy;
use Cerberus\PDP\Policy\VariableDefinition;

class VariableDefinitionFactory extends PolicyElementFactory
{
    /**
     * @param Policy $policy
     * @param array  $data
     *
     * @return PolicyElement|VariableDefinition
     */
    public static function create(Policy $policy, array $data): PolicyElement
    {
        $variableDefinition = new VariableDefinition($data['variableId']);
        if (isset($data['apply'])) {
            $apply = ApplyFactory::create($policy, $data['apply']);
            $variableDefinition->setExpression($apply);
        }

        return $variableDefinition;
    }
}
