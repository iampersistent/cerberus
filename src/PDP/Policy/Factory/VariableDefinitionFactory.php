<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory;

use Cerberus\PDP\Policy\VariableDefinition;

class VariableDefinitionFactory
{
    public static function create($data): VariableDefinition
    {
        $variableDefinition = new VariableDefinition($data['variableId']);
        if (isset($data['apply'])) {
            $apply = ApplyFactory::create($data['apply']);
            $variableDefinition->setExpression($apply);
        }

        return $variableDefinition;
    }
}