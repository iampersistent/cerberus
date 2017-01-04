<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Policy\Expressions\VariableReference;
use Cerberus\PDP\Policy\Policy;

class VariableReferenceFactory extends PolicyElementFactory
{
    /**
     * @param Policy $policy
     * @param array  $data
     *
     * @return PolicyElement|VariableReference
     */
    public static function create(Policy $policy, array $data): PolicyElement
    {
        return new VariableReference($policy, $data['variableId']);
    }
}
