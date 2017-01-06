<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\FunctionDefinition;

use Cerberus\Core\AttributeValue;
use Cerberus\Core\DataType\DataType;
use Cerberus\Core\DataType\DataTypeBoolean;
use Cerberus\Core\Identifier;
use Cerberus\Core\Status;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\ConvertedArgument;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\ExpressionResultError;
use Cerberus\PDP\Policy\FunctionDefinition;
use Ds\Set;

class FunctionDefinitionBagIsIn extends FunctionDefinition
{
    public function __construct($id, DataType $argDataType)
    {
        parent::__construct($id, new DataTypeBoolean(), $argDataType, false);
    }

    public function evaluate(
        EvaluationContext $evaluationContext,
        Set $arguments
    ): ExpressionResult
    {
        if (2 !== $arguments->count()) {
            return new ExpressionResultError(Status::createProcessingError(
                $this->getShortFunctionId() . ' Expected 2 arguments, got ' . $arguments->count()));
        }

        $argument = $arguments->get(0);

        $convertedTargetArgument = new ConvertedArgument($argument, $this->getDataTypeArgs(), false);
        if (! $convertedTargetArgument->isOk()) {
            return new ExpressionResultError($this->getFunctionStatus($convertedTargetArgument->getStatus()));
        }

        // Special case: Most methods want the value contained in the AttributeValue object inside the
        // FunctionArgument.
        // This one wants the AttributeValue itself.
        // We use the ConvertedArgument constructor to validate that the $argument is ok, then use the
        // AttributeValue
        // from the FunctionArgument.
        $attributeValue = $argument->getValue();

        $bagArgument = $arguments->get(1);
        $convertedBagArgument = new ConvertedArgument($bagArgument, null, true);

        if (! $convertedBagArgument->isOk()) {
            return new ExpressionResultError($this->getFunctionStatus($convertedBagArgument->getStatus()));
        }

        $bag = $convertedBagArgument->getBag();

        foreach ($bag->getAttributeValues() as $bagValue) {

            /*
            * Should we be checking the type of the bag contents and returning an error if the bag contents
            * are not of the right type? The spec does not say this, this should change to $attributeValue->equals()
             * if needed
            */
            if ($attributeValue->getValue() === $bagValue->getValue()) {
                return new ExpressionResult(Status::createOk(), new AttributeValue(Identifier::DATATYPE_BOOLEAN, true));
            }
        }

        return new ExpressionResult(Status::createOk(), new AttributeValue(Identifier::DATATYPE_BOOLEAN, false));
    }

}
