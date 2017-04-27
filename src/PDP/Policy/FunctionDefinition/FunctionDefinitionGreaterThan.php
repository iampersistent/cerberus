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

class FunctionDefinitionGreaterThan extends FunctionDefinition
{
    public function __construct($identifier, $dataType)
    {
        parent::__construct($identifier, new DataTypeBoolean(), $dataType, false);
    }

    public function evaluate(EvaluationContext $evaluationContext, Set $arguments): ExpressionResult
    {
        if (2 !== $arguments->count()) {
            return new ExpressionResultError(Status::createProcessingError(
                $this->getShortFunctionId() . ' expected 2 arguments, got ' . $arguments->count()));
        }

        $firstValue = new ConvertedArgument($arguments->get(0), $this->argsDataType, false);
        $secondValue = new ConvertedArgument($arguments->get(1), $this->argsDataType, false);

        return new ExpressionResult(Status::createOk(), new AttributeValue(DataTypeIdentifier::BOOLEAN, $firstValue->getValue() > $secondValue->getValue()));
    }
}
