<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\FunctionDefinition;

use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\FunctionArgument;
use Cerberus\PDP\Policy\FunctionDefinition;

class FunctionDefinitionEquality extends FunctionDefinition
{
    public function __construct($id, $dataType)
    {
        $this->dataType = $dataType;
        $this->id = $id;
    }

    public function evaluate(
        EvaluationContext $evaluationContext,
        FunctionArgument ...$arguments
    ): ExpressionResult
    {
        // TODO: Implement evaluate() method.
    }

}