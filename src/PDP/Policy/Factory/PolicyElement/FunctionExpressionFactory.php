<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Policy\Expressions\FunctionExpression;
use Cerberus\PDP\Policy\Policy;

class FunctionExpressionFactory extends PolicyElementFactory
{
    public static function create(Policy $policy, array $data): PolicyElement
    {
        return new FunctionExpression($data['functionId']);
    }
}
