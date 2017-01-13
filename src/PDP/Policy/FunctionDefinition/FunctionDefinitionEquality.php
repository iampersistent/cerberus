<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\FunctionDefinition;

use Cerberus\PDP\Policy\Expressions\AttributeValue;
use Cerberus\Core\DataType\DataType;
use Cerberus\Core\DataType\DataTypeBoolean;
use Cerberus\Core\Identifier;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\ExpressionResultError;
use Cerberus\PDP\Policy\ExpressionResultSingle;
use Cerberus\PDP\Policy\FunctionDefinition;
use Ds\Map;
use Ds\Queue;
use Ds\Set;

class FunctionDefinitionEquality extends FunctionDefinitionSimple
{
    public function __construct($id, DataType $argsDataType)
    {
        parent::__construct($id, new DataTypeBoolean(), $argsDataType, 2);
    }

    public function evaluate(
        EvaluationContext $evaluationContext,
        Set $arguments
    ): ExpressionResult {
        $convertedArguments = new Map();
        $status = $this->validateArguments($arguments, $convertedArguments);

        if (! $status->getStatusCode()->is(StatusCode::STATUS_CODE_OK)) {
            return new ExpressionResultError($this->getFunctionStatus($status));
        }

        $result = $this->isEqual($convertedArguments->get(0), $convertedArguments->get(1));

        return new ExpressionResultSingle(new AttributeValue(Identifier::DATATYPE_BOOLEAN, $result));
    }

    protected function isEqual($v1, $v2): bool
    {
        return $v1 === $v2;
    }
}
