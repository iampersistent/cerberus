<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\FunctionDefinition;

use Cerberus\Core\AttributeValue;
use Cerberus\Core\Identifier;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\ExpressionResultSingle;
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
        $arguments
    ): ExpressionResult
    {
        $result = $arguments[0]->getValue()->getValue() === $arguments[1]->getValue()->getValue();

        return new ExpressionResultSingle(new AttributeValue(Identifier::DATATYPE_BOOLEAN, $result));
    }

}