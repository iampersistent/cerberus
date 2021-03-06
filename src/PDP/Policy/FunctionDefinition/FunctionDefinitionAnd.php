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

class FunctionDefinitionAnd extends FunctionDefinition
{
    public function __construct($identifier)
    {
        parent::__construct($identifier, new DataTypeBoolean(), new DataTypeBoolean(), false);
    }

    public function evaluate(EvaluationContext $evaluationContext, Set $arguments): ExpressionResult
    {
        if ($arguments->count() === 0) {
            return new ExpressionResult(Status::createOk(), new AttributeValue(DataTypeIdentifier::BOOLEAN, true));
        }

        foreach($arguments as $argument) {
            $converted = new ConvertedArgument($argument, new DataTypeBoolean(), false);
            if (! $converted->isOk()) {
                return new ExpressionResultError($this->getFunctionStatus($converted->getStatus()));
            }

            // any false returns false
            if (! $converted->getValue()) {
                return new ExpressionResult(Status::createOk(), new AttributeValue(DataTypeIdentifier::BOOLEAN, false));
            }
        }

        return new ExpressionResult(Status::createOk(), new AttributeValue(DataTypeIdentifier::BOOLEAN, true));
    }
}
