<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\AttributeValue;
use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Exception\EvaluationException;

class FunctionArgumentExpression extends FunctionArgument
{
    protected $expression;
    protected $evaluationContext;
    protected $expressionResult;
    protected $policyDefaults;

    public function __construct(
        Expression $expression,
        EvaluationContext $evaluationContext,
        PolicyDefaults $policyDefaults
    ) {
        $this->expression = $expression;
        $this->evaluationContext = $evaluationContext;
        $this->policyDefaults = $policyDefaults;
    }

    public function isBag(): bool
    {
        return $this->getExpressionResult()->isBag();
    }

    public function getBag()
    {
        return $this->getExpressionResult()->getBag();
    }

    public function getValue(): AttributeValue
    {
        return $this->evaluateExpression()->getValue();
    }

    protected function getExpressionResult(): ExpressionResult
    {
        if (! $this->expressionResult) {
            $this->evaluateExpression();
        }

        return $this->expressionResult;
    }

    protected function evaluateExpression(): ExpressionResult
    {
        try {
            $this->expressionResult = $this->expression->evaluate($this->evaluationContext,
                $this->policyDefaults);
        } catch (EvaluationException $e) {
            $this->expressionResult = new ExpressionResultError(new Status(StatusCode::STATUS_CODE_PROCESSING_ERROR(),
                $e->getMessage()));
        }

        return $this->expressionResult;
    }
}