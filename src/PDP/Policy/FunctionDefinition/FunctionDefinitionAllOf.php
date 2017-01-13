<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\FunctionDefinition;

use Cerberus\Core\DataType\DataType;
use Cerberus\Core\DataType\DataTypeBoolean;
use Cerberus\Core\Identifier;
use Cerberus\Core\Status;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\ConvertedArgument;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\ExpressionResultError;
use Cerberus\PDP\Policy\Expressions\Apply;
use Cerberus\PDP\Policy\Expressions\AttributeValue;
use Cerberus\PDP\Policy\Expressions\FunctionExpression;
use Cerberus\PDP\Policy\FunctionDefinition;
use Ds\Set;

class FunctionDefinitionAllOf extends FunctionDefinition
{
    public function __construct($identifier, DataType $argsDataType)
    {
        parent::__construct($identifier, new DataTypeBoolean(), $argsDataType, true);
    }

    public function evaluate(
        EvaluationContext $evaluationContext,
        Set $arguments
    ): ExpressionResult {
        $attributeValue = null;
        $bag = null;
        $function = null;
        foreach ($arguments as $argument) {
            switch (get_class($argument->getExpression())) {
                case AttributeValue::class:
                    $attributeValue = $argument;
                    break;
                case Apply::class:
                    $bag = $argument;
                    break;
                case FunctionExpression::class:
                    $function = $argument;
                    break;
                default:
                    return new ExpressionResultError(
                        Status::createProcessingError(
                            ' Invalid element ' . (get_class($argument))
                        )
                    );

            }
        }
        if (! $attributeValue || ! $bag || ! $function) {
            return new ExpressionResultError(
                Status::createProcessingError(
                    'function:all-of requires a function, attribute value and apply elements'
                )
            );
        }

        $convertedBag = new ConvertedArgument($bag, null, true);
        if (! $convertedBag->isOk()) {
            return new ExpressionResultError($this->getFunctionStatus($convertedBag->getStatus()));
        }

        $functionFactory = $evaluationContext->getFunctionDefinitionFactory();

        $bag = $convertedBag->getBag();
        foreach ($bag->getAttributeValues() as $bagValue) {
            if ($attributeValue->getValue() === $bagValue->getValue()) {
                return new ExpressionResult(Status::createOk(), new AttributeValue(Identifier::DATATYPE_BOOLEAN, false));
            }
        }
    }


}
