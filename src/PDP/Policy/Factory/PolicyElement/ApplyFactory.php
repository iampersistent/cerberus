<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Policy\Expressions\Apply;
use Cerberus\PDP\Policy\Policy;

class ApplyFactory extends PolicyElementFactory
{
    /**
     * @param Policy $policy
     * @param array  $data
     *
     * @return PolicyElement|Apply
     */
    public static function create(Policy $policy, array $data): PolicyElement
    {
        $description = $data['description'] ?? '';
        $apply = new Apply($data['functionId'], $description);
        if (isset($data['apply'])) {
            foreach ($data['apply'] as $applyData) {
                $applyArgument = ApplyFactory::create($policy, $applyData);
                $apply->addArgument($applyArgument);
            }
        }
        if (isset($data['attributeDesignator'])) {
            $attributeDesignator = AttributeDesignatorFactory::create($policy, $data['attributeDesignator']);
            $apply->addArgument($attributeDesignator);
        }
        if (isset($data['attributeSelector'])) {
            $attributeDesignator = AttributeSelectorFactory::create($policy, $data['attributeSelector']);
            $apply->addArgument($attributeDesignator);
        }

        return $apply;
    }
}
