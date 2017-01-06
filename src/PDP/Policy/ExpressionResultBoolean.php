<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\AttributeValue;
use Cerberus\Core\Identifier;
use Cerberus\Core\Status;

class ExpressionResultBoolean extends ExpressionResult
{
    public function __construct(bool $value, Status $status)
    {
        $this->attributeValue = new AttributeValue(Identifier::DATATYPE_BOOLEAN, $value);
        parent::__construct($status);
    }

    public function isTrue(): bool
    {
        return (bool) $this->getValue()->getValue();
    }
}
