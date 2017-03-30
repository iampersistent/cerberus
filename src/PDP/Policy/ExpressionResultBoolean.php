<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Enums\DataTypeIdentifier;
use Cerberus\PDP\Policy\Expressions\AttributeValue;
use Cerberus\Core\Status;

class ExpressionResultBoolean extends ExpressionResult
{
    public function __construct(bool $value, Status $status)
    {
        $this->attributeValue = new AttributeValue(DataTypeIdentifier::BOOLEAN, $value);
        parent::__construct($status);
    }

    public function isTrue(): bool
    {
        return (bool) $this->getValue()->getValue();
    }
}
