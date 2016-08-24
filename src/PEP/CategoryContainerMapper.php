<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

class CategoryContainerMapper extends ObjectMapper
{
    public function map($object, PepRequest $pepRequest)
    {
        $pepRequestAttributes = $pepRequest->getPepRequestAttributes($object->getCategoryIdentifier()); // PepRequestAttributes
        $attributesMap = $object->getAttributeMap();
        if ($attributesMap) {
            foreach ($attributesMap->entrySet() as $entry) {
                $attributeId = $this->resolveAttributeId($entry->getKey());
                $value = $entry->getValue();
                if (! empty($value)) {
                    $pepRequestAttributes->addAttribute($attributeId, $value);
                } else {
                    //logger.error("No value assigned for attribute : " + attributeId);
                    throw new IllegalArgumentException("No or null value for attribute : " + $attributeId);
                }
            }
        }
    }
}