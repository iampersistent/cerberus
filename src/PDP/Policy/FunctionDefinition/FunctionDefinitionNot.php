<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\FunctionDefinition;

use Cerberus\Core\DataType\DataTypeBoolean;
use Cerberus\Core\Enums\DataTypeIdentifier;
use Cerberus\Core\Status;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\{
    ConvertedArgument,
    ExpressionResult,
    ExpressionResultError,
    Expressions\AttributeValue,
    FunctionDefinition
};
use Ds\Set;

class FunctionDefinitionNot extends FunctionDefinition
{
    public function __construct($identifier)
    {
        parent::__construct($identifier, new DataTypeBoolean(), new DataTypeBoolean(), false);
    }

    public function evaluate(EvaluationContext $evaluationContext, Set $arguments): ExpressionResult
    {
        if ($arguments->count() !== 1) {
            return new ExpressionResult(Status::createProcessingError(), 'expected 1 attribute, saw ' . $arguments->count());
        }

        $converted = new ConvertedArgument($arguments->get(0), new DataTypeBoolean(), false);

        return new ExpressionResult(Status::createOk(), new AttributeValue(DataTypeIdentifier::BOOLEAN, ! $converted->getValue()));
    }
}
