<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\DataType\DataType;
use Cerberus\Core\Identifier;
use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Exception;

class ConvertedArgument
{
    protected $bag;
    protected $status;
    protected $value;

    public function __construct(FunctionArgument $functionArgument, DataType $expectedDataType = null, bool $expectBag)
    {
        if (! $functionArgument->isOk()) {
            $this->status = $functionArgument->getStatus();

            return;
        }

        // bags are valid arguments for some functions
        if ($expectBag) {
            if (! $functionArgument->isBag()) {
                $this->status = new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                    'Expected a bag, saw a simple value');

                return;
            }

            $this->value = $functionArgument->getBag();
            $this->status = new Status(StatusCode::STATUS_CODE_OK());

            return;
        }

        // argument should not be a bag
        if ($functionArgument->isBag()) {
            $this->status = new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                'Expected a simple value, saw a bag');

            return;
        }

        $attributeValue = $functionArgument->getValue();
        if (! $attributeValue || ! $attributeValue->getValue()) {
            $this->status = new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), 'Got null attribute');

            return;
        }
        if ($attributeValue->getDataTypeId() !== $expectedDataType->getId()) {
            $this->status = new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                'Expected data type ' . $this->getShortDataTypeId($expectedDataType->getId()) .
                ' saw ' . $this->getShortDataTypeId($attributeValue->getDataTypeId()));

            return;
        }

        try {
            $this->value = $expectedDataType->convert($attributeValue->getValue());
            $this->status = new Status(StatusCode::STATUS_CODE_OK());
        } catch (Exception $e) {
            $message = $e->getMessage();

            $this->status = new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(), $message);
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

    public function getShortDataTypeId(Identifier $identifier): string
    {
        return $identifier->getValue();
    }
}