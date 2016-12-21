<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Identifier;
use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\Expressions\Apply;
use Cerberus\PDP\Policy\Expressions\AttributeDesignator;
use Cerberus\PDP\Policy\Traits\PolicyComponent;
use Ds\Set;

class Condition
{
    use PolicyComponent;

    /** @var Expression */
    protected $expression;

    public function __construct(array $data)
    {
        $arguments = new Set();
        foreach ($data['apply']['apply'] as $applyData) {
            $attribute = new AttributeDesignator(
                $applyData['attributeDesignator']['category'],
                $applyData['attributeDesignator']['dataType'],
                $applyData['attributeDesignator']['mustBePresent'],
                $applyData['attributeDesignator']['attributeId']
            );
            $arguments->add(new Apply($applyData['functionId'], new Set([$attribute])));
        }
        $this->expression = new Apply($data['apply']['functionId'], $arguments);
    }

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

        if ($attributeValueResult->getDataTypeId() !== Identifier::DATATYPE_BOOLEAN) {
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