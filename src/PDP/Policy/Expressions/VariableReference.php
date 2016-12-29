<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Expressions;

use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\Expression;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\ExpressionResultError;
use Cerberus\PDP\Policy\Policy;
use Cerberus\PDP\Policy\PolicyDefaults;
use Cerberus\PDP\Policy\VariableDefinition;

class VariableReference extends Expression
{
    protected $policy;
    protected $variableId;
    protected $variableDefinition;

    public function __construct(Policy $policy, string $variableId)
    {
        $this->policy = $policy;
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
            return new ExpressionResultError(Status::createSyntaxError(
                "Missing Expression for VariableDefinition $this->variableId"));
        }

        return $expression->evaluate($evaluationContext, $policyDefaults);
    }

    public function getPolicy(): Policy
    {
        return $this->policy;
    }

    public function getVariableId(): string
    {
        return $this->variableId;
    }

    protected function getVariableDefinition(): VariableDefinition
    {
        if (! $this->variableDefinition) {
            $this->variableDefinition = $this->policy->getVariableDefinition($this->variableId);
        }

        return $this->variableDefinition;
    }

    protected function validateComponent(): bool
    {
        $this->setStatus(StatusCode::STATUS_CODE_OK());

        return true;
    }
}