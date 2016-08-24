<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

class ResourceMapper extends CategoryContainerMapper
{
    protected $className = Resource::class;

    protected function resolveAttributeId(string $attributeId): string
    {
//        if (attributeId.equals(Resource.RESOURCE_ID_KEY)) {
//            return getPepConfig().getDefaultResourceId();
//        }
        return $attributeId;
    }
}