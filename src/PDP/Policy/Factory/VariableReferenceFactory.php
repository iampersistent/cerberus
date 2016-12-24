<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory;

use Cerberus\PDP\Policy\Expressions\VariableReference;
use Cerberus\PDP\Policy\Policy;

class VariableReferenceFactory
{
    public static function create(Policy $policy, array $data): VariableReference
    {
        return new VariableReference($policy, $data['variableId']);
    }
}