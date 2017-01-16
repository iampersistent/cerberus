<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Policy\Policy;
use Cerberus\PDP\Policy\VariableDefinition;

class VariableDefinitionFactory extends ExpressionParentFactory
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
        unset($data['variableId']);
        parent::processIncomingData($policy, $variableDefinition, $data);

        return $variableDefinition;
    }
}
