<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Exception;
use Cerberus\Core\DataType\DataType;
use Cerberus\Core\Enums\DataTypeIdentifier;
use Cerberus\Core\Status;

class ConvertedArgument
{
    protected $bag;
    protected $status;
    protected $value;

    public function __construct(FunctionArgument $functionArgument, DataType $expectedDataType = null, bool $expectBag = false)
    {
        if (! $functionArgument->isOk()) {
            $this->status = $functionArgument->getStatus();

            return;
        }

        // bags are valid arguments for some functions
        if ($expectBag) {
            if (! $functionArgument->isBag()) {
                $this->status = Status::createProcessingError('Expected a bag, saw a simple value');

                return;
            }

            $this->value = $functionArgument->getBag();
            $this->status = Status::createOk();

            return;
        }

        // argument should not be a bag
        if ($functionArgument->isBag()) {
            $this->status = Status::createProcessingError('Expected a simple value, saw a bag');

            return;
        }

        $attributeValue = $functionArgument->getValue();
        if (! $attributeValue || $attributeValue->getValue() === null) {
            $this->status = Status::createProcessingError('Got null attribute');

            return;
        }
        if ($expectedDataType && $attributeValue->getDataTypeId() !== $expectedDataType->getType()->getValue()) {
            $this->status = Status::createProcessingError(
                'Expected data type ' . $this->getShortDataTypeId($expectedDataType->getType()) .
                ' saw ' . $this->getShortDataTypeId($attributeValue->getDataTypeId()));

            return;
        }

        try {
            $this->value = $expectedDataType ? $expectedDataType->convert($attributeValue->getValue()) : $attributeValue->getValue();
            $this->status = Status::createOk();
        } catch (Exception $e) {
            $message = $e->getMessage();

            $this->status = Status::createProcessingError($message);
        }
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function isOk(): bool
    {
        return $this->status->isOk();
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getBag(): Bag
    {
        return $this->value;
    }

    public function getShortDataTypeId(DataTypeIdentifier $identifier): string
    {
        return $identifier->getValue();
    }
}
