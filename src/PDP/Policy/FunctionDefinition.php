<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\PDP\Evaluation\EvaluationContext;

abstract class FunctionDefinition
{
    protected $dataTypeId;
    protected $id;

    public function getId()
    {
        return $this->id;
    }

    public function getDataType()
    {
        return $this->dataTypeId;
    }

    public function returnsBag(): bool
    {
        return false;
    }

    /**
     * @param EvaluationContext    $evaluationContext
     * @param FunctionDefinition[] $arguments
     *
     * @return ExpressionResult
     */
    abstract public function evaluate(
        EvaluationContext $evaluationContext,
        $arguments
    ): ExpressionResult;
}