<?php

namespace TestData\PEP\Mappers;

use Cerberus\Core\Enums\{AttributeIdentifier, ResourceIdentifier};
use Cerberus\PDP\Policy\Content;
use Cerberus\PEP\{ObjectMapper, PepRequest};
use TestData\ChildObject;

class ChildObjectMapper extends ObjectMapper
{
    protected $className = ChildObject::class;

    public function map($object, PepRequest $pepRequest)
    {
        /** @var ChildObject $object */
        $pepRequestAttributes = $pepRequest->getPepRequestAttributes(AttributeIdentifier::RESOURCE_CATEGORY);

        $parentIds = [];
        if ($parent = $object->getParent()) {
            $parentIds[] = $parent->getId();
        }

        $childData = [
            'resource' => [
                'parentObjectIds' => $parentIds,
            ],
        ];

        $pepRequestAttributes
            ->addContent('childObject', new Content($childData))
            ->addAttribute(ResourceIdentifier::RESOURCE_ID, $object->getId())
            ->addAttribute(ResourceIdentifier::RESOURCE_TYPE, $this->className);
    }
}