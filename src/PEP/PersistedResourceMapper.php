<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Enums\{
    AttributeIdentifier, ContextSelectorIdentifier, ResourceIdentifier, SubjectIdentifier
};
use Cerberus\Core\Exception\IllegalArgumentException;
use Cerberus\PDP\Policy\Content;
use Cerberus\PIP\Contract\PermissionRepository;

class PersistedResourceMapper extends ObjectMapper
{
    protected $className = Content::class;
    protected $repository;

    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CategoryContainer $object
     * @param PepRequest        $pepRequest
     *
     * @throws IllegalArgumentException
     */
    public function map($object, PepRequest $pepRequest)
    {
        $subject = $pepRequest->getPepRequestAttributes(SubjectIdentifier::ACCESS_SUBJECT_CATEGORY);
        $resource = $pepRequest->getPepRequestAttributes(AttributeIdentifier::RESOURCE_CATEGORY);

        $retrievedData = $this->find($subject, $resource);

        foreach ($pepRequest->getRequestAttributes() as $requestAttribute) {
            if ($requestAttribute->getCategory() === ContextSelectorIdentifier::CONTENT_SELECTOR) {
                continue;
            }
            $content = $retrievedData ? new Content($retrievedData->toPathArray()) : new Content([]);
            $requestAttribute->addContent(ContextSelectorIdentifier::CONTENT_SELECTOR, $content);
        }
    }

    public function find(PepRequestAttributes $subject, PepRequestAttributes $resource)
    {
        $getAttributeValue = function($attribute, $attributeId) {
            return $attribute->getAttribute($attributeId)->getValues()->first()->getValue();
        };

        return $this->repository->findByIdentifiers([
            'subjectId'    => $getAttributeValue($subject, SubjectIdentifier::SUBJECT_ID),
            'subjectType'  => $getAttributeValue($subject, SubjectIdentifier::SUBJECT_TYPE),
            'resourceId'   => $getAttributeValue($resource, ResourceIdentifier::RESOURCE_ID),
            'resourceType' => $getAttributeValue($resource, ResourceIdentifier::RESOURCE_TYPE),
        ]);
    }
}
