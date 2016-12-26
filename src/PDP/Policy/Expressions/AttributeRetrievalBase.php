<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Expressions;

use Cerberus\Core\AttributeValue;
use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Policy\Expression;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\ExpressionResultError;

abstract class AttributeRetrievalBase extends Expression
{
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

    protected function getEmptyResult(string $statusMessage): ExpressionResult
    {
        if ($this->getMustBePresent()) {
            return new ExpressionResultError(Status::createProcessingError($statusMessage));
        }

        return new ExpressionResult(Status::createOk(), new AttributeValue($this->dataTypeId, null));
    }

    protected function validateComponent(): bool
    {
        if (null === $this->getCategory()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Missing Category');

            return false;
        }
        if (null === $this->getDataTypeId()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Missing DataType');

            return false;
        }
        if (null === $this->getMustBePresent()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Missing MustBePresent');

            return false;
        }

        return true;
    }
}