<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Policy\Expressions\AttributeSelector;
use Cerberus\PDP\Policy\Policy;

class AttributeSelectorFactory extends PolicyElementFactory
{
    /**
     * @param Policy $policy
     * @param array  $data
     *
     * @return PolicyElement|AttributeSelector
     */
    public static function create(Policy $policy, array $data): PolicyElement
    {
        $attributeSelector = new AttributeSelector(
            $data['category'],
            $data['dataType'],
            $data['mustBePresent'],
            $data['path']
        );

        if (isset($data['contextSelectorId'])) {
            $attributeSelector->setContextSelectorId($data['contextSelectorId']);
        }

        return $attributeSelector;
    }
}
