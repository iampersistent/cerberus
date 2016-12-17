<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\AttributeValue;
use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;

class ExpressionResultSingle extends ExpressionResult
{
    public function __construct(AttributeValue $attributeValue)
    {
        $this->attributeValue = $attributeValue;
        parent::__construct(new Status(StatusCode::STATUS_CODE_OK()));
    }
}