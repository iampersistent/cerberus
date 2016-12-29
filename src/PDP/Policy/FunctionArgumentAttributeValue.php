<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

class FunctionArgumentAttributeValue extends FunctionArgument
{
    public function __construct($attributeValue)
    {
        $this->attributeValue = $attributeValue;
    }
}