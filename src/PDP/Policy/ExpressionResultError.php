<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\AttributeValue;
use Cerberus\Core\Identifier;
use Cerberus\Core\Status;

class ExpressionResultError extends ExpressionResult
{
    public function __construct(Status $status)
    {
        parent::__construct($status, new AttributeValue(Identifier::DATATYPE_INDETERMINATE, null));
    }
}