<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Expressions;

use Cerberus\Core\StatusCode;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\Expression;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\ExpressionResultError;
use Cerberus\PDP\Policy\ExpressionResultSingle;
use Cerberus\PDP\Policy\PolicyDefaults;
use Exception;

class FunctionExpression extends Expression
{
    /** @var AttributeValue */
    protected $attributeValue;
    protected $expressionResultOk;
    protected $functionId;

    public function __construct($functionId)
    {
        $this->functionId = $functionId;
    }

    public function getFunctionId()
    {
        return $this->functionId;
    }

    public function evaluate(EvaluationContext $evaluationContext, PolicyDefaults $policyDefaults): ExpressionResult
    {
        if (! $this->validate()) {
            return new ExpressionResultError($this->getStatus());
        } else {
            return $this->getExpressionResultOk();
        }
    }

    public function getAttributeValue(): AttributeValue
    {
        if (! $this->attributeValue) {

            try {
                $this->attributeValue = ''; # evaluate through function definition through factory  DataTypes.DT_ANYURI.createAttributeValue(thisFunctionId);
            } catch (Exception $e) {
                $this->attributeValue = null;
            }
        }

        return $this->attributeValue;
    }

    protected function getExpressionResultOk(): ExpressionResult
    {
        if (! $this->expressionResultOk) {
            $this->expressionResultOk = new ExpressionResultSingle($this->getAttributeValue());
        }

        return $this->expressionResultOk;
    }

    protected function validateComponent(): bool
    {
        $this->setStatus(StatusCode::STATUS_CODE_OK());

        return true;
    }

}
