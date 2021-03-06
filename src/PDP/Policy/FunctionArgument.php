<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\PDP\Policy\Expressions\AttributeValue;
use Cerberus\Core\Status;

abstract class FunctionArgument
{
    /** @var AttributeValue */
    protected $attributeValue;
    /** @var Bag */
    protected $bag;
    /** @var Status */
    protected $status;

    public function isBag(): bool
    {
        return (bool) $this->bag;
    }

    public function getBag()
    {
        return $this->bag;
    }

    public function isOk(): bool
    {
        return ! $this->getStatus() || $this->getStatus()->isOk();
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getValue(): AttributeValue
    {
        return $this->attributeValue;
    }
}
