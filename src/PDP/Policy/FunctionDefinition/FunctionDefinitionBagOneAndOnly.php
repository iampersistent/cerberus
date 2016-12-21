<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\FunctionDefinition;

use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\ConvertedArgument;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\ExpressionResultError;
use Cerberus\PDP\Policy\ExpressionResultSingle;
use Cerberus\PDP\Policy\FunctionArgument;
use Cerberus\PDP\Policy\FunctionDefinition;
use Ds\Set;

class FunctionDefinitionBagOneAndOnly extends FunctionDefinition
{
    public function evaluate(EvaluationContext $evaluationContext, Set $arguments): ExpressionResult
    {
        if (! $arguments || $arguments->count() !== 1) {
            return new ExpressionResultError(Status::createProcessingError(
                $this->getShortFunctionId()
                . ' Expected 1 argument, got '
                . (! $arguments ? 'null' : $arguments->count())));
        }

        /** @var FunctionArgument $argument */
        $argument = $arguments->get(0);
        $convertedArgument = new ConvertedArgument($argument, null, true);

        if (! $convertedArgument->isOk()) {
            return new ExpressionResultError($this->getFunctionStatus($convertedArgument->getStatus()));
        }

        $bag = $convertedArgument->getBag();

        if ($bag->size() !== 1) {
            return new ExpressionResultError(Status::createProcessingError(
                $this->getShortFunctionId() . ' Expected 1 but Bag has '
                . $bag->size() . ' elements'));
        }

        // get the single value from the bag
        $attributeValueOneAndOnly = $bag->getAttributeValues()->first();

// make sure it has the right type
//
        if ($this->getDataTypeId() !== $attributeValueOneAndOnly->getDataTypeId()) {
            return new ExpressionResultError(Status::createProcessingError(
                $this->getShortFunctionId()
                . ' Element in bag of wrong type-> Expected '
                . $this->getShortDataType($this->getDataTypeId())
                . ' got '
                . $this->getShortDataTypeId($attributeValueOneAndOnly->getDataTypeId())));
        }

        return new ExpressionResultSingle($attributeValueOneAndOnly);
    }
}