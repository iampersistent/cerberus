<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Expressions;

use Cerberus\Core\Status;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\Expression;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\ExpressionResultError;
use Cerberus\PDP\Policy\Policy;
use Cerberus\PDP\Policy\PolicyDefaults;

class VariableReference extends Expression
{
    protected $policy;
    protected $variableId;
    protected $variableDefinition;

    protected function getVariableDefinition(): VariableDefinition
    {
        if (! $this->variableDefinition) {
            if ($policy = $this->getPolicy()) {
                if ($variableId = $this->getVariableId()) {
                    $this->variableDefinition = $policy->getVariableDefinition($variableId);
                }
            }
        }

        return $this->variableDefinition;
    }


    public function getPolicy(): Policy
    {
        return $this->policy;
    }

    public function setPolicy(Policy $policy)
    {
        $this->policy = $policy;
    }

    public function getVariableId(): string
    {
        return $this->variableId;
    }

    public function setVariableId(string $variableId)
    {
        $this->variableId = $variableId;
    }

    public function evaluate(EvaluationContext $evaluationContext, PolicyDefaults $policyDefaults): ExpressionResult
    {
        if (! $this->validate()) {
            return new ExpressionResultError($this->getStatus());
        }

        if (! $variableDefinition = $this->getVariableDefinition()) {
            return new ExpressionResultError(Status::createProcessingError(
                "No VariableDefinition found for $this->variableId"));
        }
        if (! $expression = $variableDefinition->getExpression()) {
            return new ExpressionResultError(Status::createSyntaxError('Missing Expression for VariableDefinition'));
        }

        return $expression->evaluate($evaluationContext, $policyDefaults);
    }


    protected function validateComponent(): bool
    {
        if ($this->getVariableId() == null) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), "Missing VariableId");

            return false;
        }
        if ($this->getPolicy() == null) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), "VariableReference not in a Policy");

            return false;
        }
        $this->setStatus(StatusCode::STATUS_CODE_OK(), null);

        return true;
    }
}