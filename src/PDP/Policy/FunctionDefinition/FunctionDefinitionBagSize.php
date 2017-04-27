<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\FunctionDefinition;

use Cerberus\Core\DataType\{
    DataTypeIndeterminate, DataTypeInteger
};
use Cerberus\Core\Status;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\{
    ConvertedArgument, ExpressionResult, ExpressionResultError, Expressions\AttributeValue, FunctionDefinition
};
use Ds\Set;

class FunctionDefinitionBagSize extends FunctionDefinition
{
    public function __construct($identifier)
    {
        parent::__construct($identifier, new DataTypeInteger(), new DataTypeIndeterminate(), false);
    }

    public function evaluate(EvaluationContext $evaluationContext, Set $arguments): ExpressionResult
    {
        if (1 !== $arguments->count()) {
            return new ExpressionResultError(Status::createProcessingError(
                $this->getShortFunctionId() . ' expected 1 argument, got ' . $arguments->count()));
        }

        $value = new ConvertedArgument($arguments->get(0), $this->argsDataType, true);

        if (! $value->isOk()) {
            return $value->getStatus();
        }

        $size = $value->getValue()->getAttributeValues()->count();
        // adjust for "content selector" that receives a "null" for empty arrays
        if ($size === 1 && $value->getValue()->getAttributeValues()->get(0)->getValue() === null) {
            $size = 0;
        }

        return new ExpressionResult(Status::createOk(),
            new AttributeValue($this->returnDataType->getType()->getValue(), $size));
    }
}
