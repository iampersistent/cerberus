<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Enums\DataTypeIdentifier;
use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\Expressions\Apply;
use Cerberus\PDP\Policy\Expressions\AttributeDesignator;
use Cerberus\PDP\Policy\Traits\PolicyComponent;
use Ds\Set;

class Condition implements PolicyElement
{
    use PolicyComponent;

    /** @var Expression */
    protected $expression;

    public function evaluate(EvaluationContext $evaluationContext, PolicyDefaults $policyDefaults)
    {
        if (! $this->validate()) {
            return new ExpressionResultBoolean(false, $this->getStatus());
        }

        $expressionResult = $this->getExpression()->evaluate($evaluationContext, $policyDefaults);
        if (! $expressionResult->isOk()) {
            return new ExpressionResultBoolean(false, $expressionResult->getStatus());
        }

        if ($expressionResult->isBag()) {
            return new ExpressionResultBoolean(false, Status::createProcessingError('Condition Expression returned a bag'));
        }

        if (! $attributeValueResult = $expressionResult->getValue()) {
            return new ExpressionResultBoolean(false, Status::createProcessingError('Null value from Condition Expression'));
        }

        if ($attributeValueResult->getDataTypeId() !== DataTypeIdentifier::BOOLEAN) {
            return new ExpressionResultBoolean(false, Status::createProcessingError('Non-boolean value from Condition Expression'));
        }

        return new ExpressionResultBoolean($attributeValueResult->getValue(), Status::createOk());
    }

    public function getExpression(): Expression
    {
        return $this->expression;
    }

    public function setExpression(Expression $expression): self
    {
        $this->expression = $expression;

        return $this;
    }

    protected function validateComponent(): bool
    {
        if (! $this->getExpression()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Missing Expression');

            return false;
        } else {
            $this->setStatus(StatusCode::STATUS_CODE_OK());

            return true;
        }
    }
}
