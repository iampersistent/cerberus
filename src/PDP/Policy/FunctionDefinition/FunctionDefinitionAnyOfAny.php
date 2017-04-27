<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\FunctionDefinition;

use Cerberus\Core\Enums\DataTypeIdentifier;
use Cerberus\PDP\Policy\Expressions\AttributeValue;
use Cerberus\Core\DataType\{
    DataType, DataTypeBoolean
};
use Cerberus\Core\Status;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\{
    ConvertedArgument,
    ExpressionResult,
    ExpressionResultError,
    FunctionDefinition
};
use Ds\Set;

class FunctionDefinitionAnyOfAny extends FunctionDefinition
{
    public function __construct($identifier, DataType $argsDataType)
    {
        parent::__construct($identifier, new DataTypeBoolean(), $argsDataType, true);
    }

    public function evaluate(EvaluationContext $evaluationContext, Set $arguments): ExpressionResult
    {
        if (2 !== $arguments->count()) {
            return new ExpressionResultError(Status::createProcessingError(
                $this->getShortFunctionId() . ' expected 2 arguments, got ' . $arguments->count()));
        }

        $bag0Arguments = new ConvertedArgument($arguments->get(0), null, true);
        if (! $bag0Arguments->isOk()) {
            return new ExpressionResultError($this->getFunctionStatus($bag0Arguments->getStatus()));
        }
        $bag0 = $bag0Arguments->getBag();

        $bag1Arguments = new ConvertedArgument($arguments->get(1), null, true);
        if (! $bag1Arguments->isOk()) {
            return new ExpressionResultError($this->getFunctionStatus($bag0Arguments->getStatus()));
        }
        $bag1 = $bag1Arguments->getBag();

        foreach ($bag0->getAttributeValues() as $bag0Value) {
            foreach ($bag1->getAttributeValues() as $bag1Value) {
                if ($bag0Value->getValue() === $bag1Value->getValue()) {
                    return new ExpressionResult(
                        Status::createOk(),
                        new AttributeValue(DataTypeIdentifier::BOOLEAN, true)
                    );
                }
            }
        }

        return new ExpressionResult(Status::createOk(), new AttributeValue(DataTypeIdentifier::BOOLEAN, false));
    }
}
