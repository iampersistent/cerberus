<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Expressions;

use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\ExpressionResult;
use Cerberus\PDP\Policy\PolicyDefaults;

class AttributeDesignator extends AttributeRetrievalBase
{
    protected $attributeId;
    
    public function getAttributeId()
    {
        return $this->attributeId;
    }

    public function evaluate(
        EvaluationContext $evaluationContext,
        PolicyDefaults $policyDefaults
    ): ExpressionResult
    {

    }
}