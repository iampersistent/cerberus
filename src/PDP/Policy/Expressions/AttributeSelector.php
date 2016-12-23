<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Expressions;

use Cerberus\Core\Exception\DataTypeException;
use Cerberus\Core\Identifier;
use Cerberus\Core\RequestAttributes;
use Cerberus\Core\Status;
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

    protected $path;

    public function getContextSelectorId(): Identifier
    {
        return $this->contextSelectorId;
    }

    public function setContextSelectorId(Identifier $identifier)
    {
        $this->contextSelectorId = $identifier;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
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

    /**
     * If there is a context selector ID, get the attributes from the given <code>RequestAttributes</code>
     * with that ID, ensure they are <code>XPathExpression</code>s and return them->
     *
     * @param $requestAttributes
     *
     * @return Set|null
     */
    protected function getContextSelectorValues(RequestAttributes $requestAttributes)
    {
        if (! $contextSelectorId = $this->getContextSelectorId()) {
            return null;
        }
        $xPathExpressionsSet = null;
        $attribute = $requestAttributes->getAttributes($contextSelectorId);
        $xPathExpressions = $attribute->findValues(Identifier::DATATYPE_XPATHEXPRESSION);
                if ($xPathExpressions && $xPathExpressions->hasNext()) {
                    if (! $xPathExpressionsSet) {
                        $xPathExpressionsSet = new Set();
                    }
                    $xPathExpressionsSet->add($xPathExpressions->next()->getValue());
                }

        return $xPathExpressionsSet;
    }


    public function evaluate(EvaluationContext $evaluationContext, PolicyDefaults $policyDefaults): ExpressionResult
    {
        if (! $this->validate()) {
            return new ExpressionResultError($this->getStatus());
        }

        /*
         * Get the DataType for this AttributeSelector for converting the resulting nodes into AttributeValues
         */
        $dataType = $this->getDataType();

        /*
        * Get the Request so we can find the XPathExpression to locate the root node and to find the Content
        * element of the $requested category->
        */
        $request = $evaluationContext->getRequest();

        /*
        * Get the RequestAttributes objects for our Category-> If none are found, then we abort quickly with
        * either an empty or indeterminate result->
        */
        $requestedAttributes = $request->getRequestAttributes($this->getCategory());
        if (! $requestedAttributes || ! $requestedAttributes->hasNext()) {
            return $this->getEmptyResult("No Attributes with Category $this->getCategory()");
        }

        /*
        * Section 5->30 of the XACML 3->0 specification is a little vague about how to use the
        * ContextSelectorId in the face of having multiple Attributes elements with the same CategoryId-> My
        * interpretation is that each is distinct, so we look for an attribute matching the ContextSelectorId
        * in each matching Attributes element and use that to search the Content in that particular
        * Attributes element-> If either an Attribute matching the context selector id is not found or there
        * is no Content, then that particular Attributes element is skipped->
        */
        $attributeValues = new Bag();
        $statusFirstError = null;
        foreach ($requestedAttributes as $requestAttributes) {

        /*
        * See if we have a Content element to query->
        */
        $contentRoot = $requestAttributes->getContentRoot();
        if ($contentRoot) {
            $nodesToQuery = new Set();
            $xPathExpressions = $this->getContextSelectorValues($requestAttributes);
            if (! $xPathExpressions) {
                $nodesToQuery->add($contentRoot);
            } else {
                foreach ($xPathExpressions as $xPathExpression) {
                    $content = $requestAttributes->getContentNodeByXpathExpression($xPathExpression);
                    if ($content) {
                        $nodesToQuery->add($content);
                    }
                }
            }

            /*
            * If there are any nodes to query, do so now and add the results
            */
            if (! $nodesToQuery->isEmpty()) {
                foreach ($nodesToQuery as $nodeToQuery) {
                    $nodeList = null;
                    try {
                        $xPath = XPathFactory->newInstance()->newXPath();
                        $xPath
                            ->setNamespaceContext(new NodeNamespaceContext(nodeToQuery->getOwnerDocument()));
                        $xPathExpression = $xPath->compile($this->getPath());
                        $nodeToQueryDocumentRoot = null;
                        try {
                            $nodeToQueryDocumentRoot = DOMUtil->getDirectDocumentChild(nodeToQuery);
                        } catch (StructureException $e) {
                            return new ExpressionResultError(Status::createProcessingError("Exception processing context node: $e->getMessage()"));
                        }
                        $nodeList = $xPathExpression->evaluate($nodeToQueryDocumentRoot,
                            XPathConstants->NODESET);
                        } catch (XPathExpressionException $e) {
                        if (! $statusFirstError == null) {
                            $statusFirstError = Status::createProcessingError("XPathExpressionException: $e->getMessage()");
                        }
                    }
                    if ($nodeList && ! $nodeList->isEmpty()) {
                        foreach ($nodeList as $node) {
                            $attributeValueNode = null;
                            try {
                                $attributeValueNode = $dataType->createAttributeValue($node);
                            } catch (DataTypeException $e) {
                                if (! $statusFirstError) {
                                    $statusFirstError = Status::createProcessingError($e->getMessage());
                                }
                            }
                            if ($attributeValueNode) {
                                $attributeValues->add($attributeValueNode);
                            } else {
                                if (! $statusFirstError) {
                                    $statusFirstError = Status::createProcessingError("Unable to convert node to $this->dataTypeId");
                                }
                            }
                        }
                    }
                }
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