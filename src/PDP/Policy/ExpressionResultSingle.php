<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\PDP\Policy\Expressions\AttributeValue;
use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;

class ExpressionResultSingle extends ExpressionResult
{
    public function __construct(AttributeValue $attributeValue)
    {
        $this->attributeValue = $attributeValue;
        parent::__construct(Status::createOk());
    }
}
