<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\FunctionDefinition;

use Cerberus\Core\AttributeValue;
use Cerberus\Core\Identifier;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\ExpressionResultSingle;
use Cerberus\PDP\Policy\FunctionDefinition;
use Ds\Set;

class FunctionDefinitionEquality extends FunctionDefinition
{
    public function evaluate(
        EvaluationContext $evaluationContext,
        Set $arguments
    ): ExpressionResult
    {
        $result = $arguments->get(0)->getValue()->getValue() === $arguments->get(1)->getValue()->getValue();

        return new ExpressionResultSingle(new AttributeValue(Identifier::DATATYPE_BOOLEAN, $result));
    }

}