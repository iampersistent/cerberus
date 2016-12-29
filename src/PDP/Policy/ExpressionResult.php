<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\AttributeValue;
use Cerberus\Core\Status;

class ExpressionResult extends FunctionArgument
{
    public function __construct(Status $status, AttributeValue $attributeValue = null)
    {
        if ($attributeValue) {
            $this->attributeValue = $attributeValue;
        }
        $this->status = $status;
    }

    public function getFunctionalDefinition($id)
    {
        throw \Exception('implement');
    }
}