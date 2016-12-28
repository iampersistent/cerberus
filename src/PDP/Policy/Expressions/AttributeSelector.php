<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Expressions;

use Cerberus\Core\AttributeValue;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\Bag;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\ExpressionResultBag;
use Cerberus\PDP\Policy\ExpressionResultError;
use Cerberus\PDP\Policy\PolicyDefaults;
use Cerberus\PDP\Policy\Traits\PolicyComponent;
use Ds\Set;

class AttributeSelector extends AttributeRetrievalBase
{
    use PolicyComponent;

    protected $contextSelectorId;
    protected $path;

    public function __construct($category, $dataTypeId, $mustBePresent, $path)
    {
        $this->path = $path;
        parent::__construct($category, $dataTypeId, $mustBePresent);
    }

    /**
     * @return string|null
     */
    public function getContextSelectorId()
    {
        return $this->contextSelectorId;
    }

    public function setContextSelectorId(string $identifier)
    {
        $this->contextSelectorId = $identifier;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    protected function validateComponent(): bool
    {
        if (! parent::validateComponent()) {
            return false;
        }
        if (! $this->getPath()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Missing Path');

            return false;
        }

        return true;
    }

    public function evaluate(EvaluationContext $evaluationContext, PolicyDefaults $policyDefaults): ExpressionResult
    {
        if (! $this->validate()) {
            return new ExpressionResultError($this->getStatus());
        }

        $request = $evaluationContext->getRequest();
        $requestedAttributes = $request->getRequestAttributes($this->category);
        if (! $requestedAttributes || $requestedAttributes->isEmpty()) {
            return $this->getEmptyResult("No Attributes with Category $this->category");
        }

        /*
         * Section 5.30 of the XACML 3.0 specification is a little vague about how to use the
         * ContextSelectorId in the face of having multiple Attributes elements with the same CategoryId. This
         * interpretation is that each is distinct, so we look for an attribute matching the ContextSelectorId
         * in each matching Attributes element and use that to search the Content in that particular
         * Attributes element. If either an Attribute matching the context selector id is not found or there
         * is no Content, then that particular Attributes element is skipped.
         */
        $dataType = $this->getDataTypeId();
        $attributeValues = new Bag();
        $statusFirstError = null;
        $nodesToQuery = new Set();
        foreach ($requestedAttributes as $requestAttributes) {
            $contentRoot = $requestAttributes->getContentRoot();
            if (! $contentRoot->isEmpty()) {
                $nodesToQuery->add($contentRoot);
            }
        }

        foreach ($nodesToQuery as $nodeToQuery) {
            foreach ($nodeToQuery as $content) {
                $data = $content->evaluate($this->getPath());
                if (is_array($data)) {
                    foreach ($data as $datum) {
                        $attributeValue = new AttributeValue($dataType, $datum);
                        $attributeValues->add($attributeValue);
                    }
                } else {
                    $attributeValue = new AttributeValue($dataType, $data);
                    $attributeValues->add($attributeValue);
                }
            }
        }

        if ($attributeValues->isEmpty()) {
            if (! $statusFirstError) {
                return $this->getEmptyResult('No Content found');
            } else {
                return new ExpressionResultError($statusFirstError);
            }
        } else {
            return new ExpressionResultBag($attributeValues);
        }
    }
}