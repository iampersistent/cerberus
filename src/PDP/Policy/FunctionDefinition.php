<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\PDP\Evaluation\EvaluationContext;

abstract class FunctionDefinition
{
    protected $dataType;
    protected $id;

    public function getId()
    {
        return $this->id;
    }

    public function getDataTypeId()
    {
        return $this->dataType;
    }

    public function returnsBag(): bool
    {
        return false;
    }

    abstract public function evaluate(
        EvaluationContext $evaluationContext,
        FunctionArgument ...$arguments
    ): ExpressionResult;
}