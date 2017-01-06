<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\FunctionDefinition;

use Cerberus\Core\DataType\DataType;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\Bag;
use Cerberus\PDP\Policy\ConvertedArgument;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\ExpressionResultBag;
use Cerberus\PDP\Policy\ExpressionResultError;
use Cerberus\PDP\Policy\FunctionDefinition;
use Ds\Set;

class FunctionDefinitionBag extends FunctionDefinition
{
    public function __construct($identifier, DataType $argsDataType)
    {
        parent::__construct($identifier, $argsDataType, $argsDataType, true);
    }

    public function evaluate(
        EvaluationContext $evaluationContext,
        Set $arguments
    ): ExpressionResult
    {
        $bag = new Bag();
        if (! $arguments->isEmpty()) {
            foreach ($arguments as $argument) {
                $convertedArgument = new ConvertedArgument($argument, $this->getDataTypeArgs(), false);

                if (!$convertedArgument->isOk()) {
                    return new ExpressionResultError($this->getFunctionStatus($convertedArgument->getStatus()));
                }

                $bag->merge($convertedArgument->getBag()->getAttributeValues());
            }
        }

        return new ExpressionResultBag($bag);
    }
}
