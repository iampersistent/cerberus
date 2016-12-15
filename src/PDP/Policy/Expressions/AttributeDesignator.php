<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Expressions;

use Cerberus\Core\Attribute;
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
    protected $issuer;
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

    public function evaluate(
        EvaluationContext $evaluationContext,
        PolicyDefaults $policyDefaults
    ): ExpressionResult
    {
        if (! $this->validate()) {
            return new ExpressionResult($this->getStatus());
        }

        /*
         * Set up the PIPRequest representing this
         */
        $pipRequest = $this->getPipRequest();

        /*
         * Query the evaluation context for results
         */
        try {
            $pipResponse = $evaluationContext->getAttributes($pipRequest);
        } catch (PipException $e) {
            throw new EvaluationException("PIPException getting Attributes", $e);
        }

        /*
         * See if the request was successful
         */
        $pipStatus = $pipResponse->getStatus();
        if ($pipStatus && ! $pipStatus->getStatusCode()->is(StatusCode::STATUS_CODE_OK)) {
            return new ExpressionResult($pipStatus);
        }

        /*
         * See if there were any results
         */
        $bagAttributeValues = new Bag();
        foreach ($pipResponse->getAttributes() as $attribute) {
            if ($this->match($attribute)) {
                foreach ($attribute->getValues() as $attributeValue) {
                    if ($this->match($attributeValue)) {
                        $bagAttributeValues->add($attributeValue);
                    }
                }
            }
        }
        if ($this->getMustBePresent() && $bagAttributeValues->count() == 0) {
            return new ExpressionResultError(new Status(StatusCode::STATUS_CODE_MISSING_ATTRIBUTE(),
                'Missing required attribute' . $this->getMissingAttributeDetail())); // originally missing attribute detail was separate param
        } else {
            return new ExpressionResultBag($bagAttributeValues);
        }
    }

    public function getIssuer()
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

    protected function match(Attribute $attribute): bool
    {
        if (! $this->getCategory() === $attribute->getCategory()) {
            return false;
        }
        if (! $this->getAttributeId() === $attribute->getAttributeId()) {
            return false;
        }
        if ($this->getIssuer() && $this->getIssuer() !== $attribute->getIssuer()) {
            return false;
        }

        return true;
    }

    protected function getPIPRequest(): PipRequest
    {
        if (! $this->pipRequest) {
            $this->pipRequest = new PipRequest($this->getCategory(), $this->getAttributeId(),
                $this->getDataTypeId(), $this->getIssuer());
        }

        return $this->pipRequest;
    }
}