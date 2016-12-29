<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\DataType\DataType;
use Cerberus\Core\Status;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Ds\Set;

abstract class FunctionDefinition
{
    protected $dataTypeId;
    protected $id;

    public function __construct($id, $dataType)
    {
        $this->dataTypeId = $dataType;
        $this->id = $id;
    }

    public function getDataTypeId()
    {
        return $this->dataTypeId;
    }

    public function getFunctionStatus(Status $originalStatus): Status
    {
        return new Status($originalStatus->getStatusCode(),
            $this->getShortFunctionId() . ' ' . $originalStatus->getStatusMessage());
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

    public function returnsBag(): bool
    {
        return false;
    }

    abstract public function evaluate(
        EvaluationContext $evaluationContext,
        Set $arguments
    ): ExpressionResult;
}