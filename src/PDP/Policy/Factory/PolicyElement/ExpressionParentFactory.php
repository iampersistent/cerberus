<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Policy\Policy;

abstract class ExpressionParentFactory extends PolicyElementFactory
{
    protected static function processApply(Policy $policy, PolicyElement $element, $data)
    {
        $apply = ApplyFactory::create($policy, $data);
        $element->setExpression($apply);
    }

    protected static function processAttributeDesignator(Policy $policy, PolicyElement $element, $data)
    {
        $attributeDesignator = AttributeDesignatorFactory::create($policy, $data);
        $element->setExpression($attributeDesignator);
    }

    protected static function processAttributeSelector(Policy $policy, PolicyElement $element, $data)
    {
        $attributeDesignator = AttributeSelectorFactory::create($policy, $data);
        $element->setExpression($attributeDesignator);
    }

    protected static function processAttributeValue(Policy $policy, PolicyElement $element, $data)
    {
        $attributeDesignator = AttributeValueFactory::create($policy, $data);
        $element->setExpression($attributeDesignator);
    }

    protected static function processVariableReference(Policy $policy, PolicyElement $element, $data)
    {
        $attributeDesignator = VariableReferenceFactory::create($policy, $data);
        $element->setExpression($attributeDesignator);
    }
}
