<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Enums\DataTypeIdentifier;
use Cerberus\PDP\Policy\Expressions\AttributeValue;
use Cerberus\Core\Status;

class ExpressionResultError extends ExpressionResult
{
    public function __construct(Status $status)
    {
        parent::__construct($status, new AttributeValue(DataTypeIdentifier::INDETERMINATE, null));
    }
}
