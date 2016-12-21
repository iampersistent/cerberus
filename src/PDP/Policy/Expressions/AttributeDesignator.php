<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Expressions;

use Cerberus\Core\Attribute;
use Cerberus\Core\AttributeValue;
use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Exception\EvaluationException;
use Cerberus\PDP\Policy\Bag;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\ExpressionResultBag;
use Cerberus\PDP\Policy\ExpressionResultError;
use Cerberus\PDP\Policy\PolicyDefaults;
use Cerberus\PIP\Exception\PipException;
use Cerberus\PIP\PipRequest;

class AttributeDesignator extends AttributeRetrievalBase
{
    protected $attributeId;
    protected $issuer = '';
    protected $pipRequest;

    public function __construct($category, $dataTypeId, $mustBePresent, $attributeId)
    {
        $this->attributeId = $attributeId;
        parent::__construct($category, $dataTypeId, $mustBePresent);
    }

    public function getAttributeId()
    {
        return $this->attributeId;
    }

    public function evaluate(EvaluationContext $evaluationContext, PolicyDefaults $policyDefaults): ExpressionResult
    {
        if (! $this->validate()) {
            return new ExpressionResultError($this->getStatus());
        }

        $pipRequest = $this->getPipRequest();

        try {
            $pipResponse = $evaluationContext->getAttributes($pipRequest);
        } catch (PipException $e) {
            throw new EvaluationException("PIPException getting Attributes", $e);
        }

        $pipStatus = $pipResponse->getStatus();
        if ($pipStatus && ! $pipStatus->getStatusCode()->is(StatusCode::STATUS_CODE_OK)) {
            return new ExpressionResult($pipStatus);
        }

        $bagAttributeValues = new Bag();
        foreach ($pipResponse->getAttributes() as $attribute) {
            if ($this->matchAttribute($attribute)) {
                foreach ($attribute->getValues() as $attributeValue) {
                    if ($this->matchAttributeValue($attributeValue)) {
                        $bagAttributeValues->add($attributeValue);
                    }
                }
            }
        }
        if ($this->getMustBePresent() && $bagAttributeValues->count() == 0) {
            return new ExpressionResultError(Status::createMissingAttribute(
                "Missing required attribute: $this->attributeId"));
        } else {
            return new ExpressionResultBag($bagAttributeValues);
        }
    }

    public function getIssuer(): string
    {
        return $this->issuer;
    }

    public function setIssuer($issuer): self
    {
        $this->issuer = $issuer;

        return $this;
    }

    protected function validateComponent(): bool
    {
        if (! parent::validateComponent()) {
            return false;
        }
        if (! $this->getAttributeId()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Missing AttributeId');

            return false;
        }

        return true;
    }

    protected function matchAttribute(Attribute $attribute): bool
    {
        if ($this->getCategory() !== $attribute->getCategory()) {
            return false;
        }
        if ($this->getAttributeId() !== $attribute->getAttributeId()) {
            return false;
        }
        if ($this->getIssuer() && $this->getIssuer() !== $attribute->getIssuer()) {
            return false;
        }

        return true;
    }

    protected function matchAttributeValue(AttributeValue $attributeValue): bool
    {
        return $this->getDataTypeId() === $attributeValue->getDataTypeId();
    }

    protected function getPipRequest(): PipRequest
    {
        if (! $this->pipRequest) {
            $this->pipRequest = new PipRequest($this->getCategory(), $this->getAttributeId(),
                $this->getDataTypeId(), $this->getIssuer());
        }

        return $this->pipRequest;
    }
}