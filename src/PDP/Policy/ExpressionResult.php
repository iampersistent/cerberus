<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

class ExpressionResult extends FunctionArgument
{
    public function __construct($status)
    {
        $this->status = $status;
    }

    public function getFunctionalDefinition($id)
    {
$doSOmething = true;
    }
}