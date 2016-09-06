<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Exception\IllegalArgumentException;

class CategoryContainerMapper extends ObjectMapper
{
    public function map($object, PepRequest $pepRequest)
    {
        $pepRequestAttributes = $pepRequest->getPepRequestAttributes($object->getCategoryIdentifier()); // PepRequestAttributes
        $attributesMap = $object->getAttributeMap();
        if ($attributesMap) {
            /** @var \Ds\Pair $pair */
            foreach ($attributesMap->pairs() as $pair) {
                $attributeId = $this->resolveAttributeId((string)$pair->key);
                $value = $pair->value;
                if (! empty($value)) {
                    $pepRequestAttributes->addAttribute($attributeId, $value);
                } else {
                    //logger.error("No value assigned for attribute : " + attributeId);
                    throw new IllegalArgumentException("No or null value for attribute : $attributeId");
                }
            }
        }
    }

    protected function resolveAttributeId(string $attributeId): string
    {
        return $attributeId;
    }
}