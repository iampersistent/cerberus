<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\DataType\DataType;
use Cerberus\Core\Factory\DataTypeFactory;
use Cerberus\Core\Identifier;
use Cerberus\Core\Status;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Ds\Set;

abstract class FunctionDefinition
{
    protected $argsDataType;
    protected $id;
    protected $returnsBag;
    protected $returnDataType;

    public function __construct($identifier, DataType $returnDataType, DataType $argsDataType, bool $returnsBag)
    {
        $this->argsDataType = $argsDataType;
        $this->id = $identifier;
        $this->returnsBag = $returnsBag;
        $this->returnDataType = $returnDataType;
    }

    public function getDataType(): DataType
    {
        return $this->returnDataType;
    }

    public function getDataTypeId(): Identifier
    {
        return $this->returnDataType->getType();
    }

    public function getDataTypeArgs(): DataType
    {
        return $this->argsDataType;
    }

    public function returnsBag(): bool
    {
        return $this->returnsBag;
    }

    public function getFunctionStatus(Status $originalStatus): Status
    {
        return new Status(
            $originalStatus->getStatusCode(),
            $this->getShortFunctionId() . ' ' . $originalStatus->getStatusMessage()
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getShortFunctionId()
    {
        $parts = explode(':', $this->id);

        return $parts[1];
    }

    public function getShortDataTypeId($identifier)
    {
        return (string) $identifier;
    }

    abstract public function evaluate(
        EvaluationContext $evaluationContext,
        Set $arguments
    ): ExpressionResult;
}
