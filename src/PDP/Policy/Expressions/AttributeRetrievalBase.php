<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Expressions;

use Cerberus\Core\StatusCode;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\PolicyDefaults;
use Cerberus\PDP\Policy\Traits\PolicyComponent;

abstract class AttributeRetrievalBase
{
    use PolicyComponent;

    protected $category;
    protected $dataTypeId;
    protected $mustBePresent;

    public function __construct(
        string $category,
        string $dataTypeId,
        bool $mustBePresent
    ) {
        $this->category = $category;
        $this->dataTypeId = $dataTypeId;
        $this->mustBePresent = $mustBePresent;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getDataTypeId(): string
    {
        return $this->dataTypeId;
    }

    public function getMustBePresent(): bool
    {
        return $this->mustBePresent;
    }

    abstract public function evaluate(EvaluationContext $evaluationContext, PolicyDefaults $policyDefaults): ExpressionResult;

    protected function validateComponent(): bool
    {
        if (null === $this->getCategory()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), "Missing Category");

            return false;
        }
        if (null === $this->getDataTypeId()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), "Missing DataType");

            return false;
        }
        if (null === $this->getMustBePresent()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), "Missing MustBePresent");

            return false;
        }

        return true;
    }
}