<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory;

use Cerberus\PDP\Policy\Expressions\Apply;

class ApplyFactory
{
    public static function create($data): Apply
    {
        $description = $data['description'] ?? '';
        $apply = new Apply($data['functionId'], $description);
        if (isset($data['apply'])) {
            foreach ($data['apply'] as $applyData) {
                $applyArgument = ApplyFactory::create($applyData);
                $apply->addArgument($applyArgument);
            }
        }
        if (isset($data['attributeDesignator'])) {
            $attributeDesignator = AttributeDesignatorFactory::create($data['attributeDesignator']);
            $apply->addArgument($attributeDesignator);
        }
        if (isset($data['attributeSelector'])) {
            $attributeDesignator = AttributeSelectorFactory::create($data['attributeSelector']);
            $apply->addArgument($attributeDesignator);
        }

        return $apply;
    }
}