<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Enums\{
    AttributeCategoryIdentifier, ContextSelectorIdentifier, ResourceIdentifier, SubjectIdentifier, SubjectCategoryIdentifier
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
        $subject = $pepRequest->getPepRequestAttributes(SubjectCategoryIdentifier::ACCESS_SUBJECT);
        $resource = $pepRequest->getPepRequestAttributes(AttributeCategoryIdentifier::RESOURCE);
        $getAttributeValue = function($attribute, $attributeId) {
            return $attribute->getAttribute($attributeId)->getValues()->first()->getValue();
        };
        $requestData = [
            'subjectId'    => $getAttributeValue($subject, SubjectIdentifier::SUBJECT_ID),
            'subjectType'  => $getAttributeValue($subject, SubjectIdentifier::SUBJECT_TYPE),
            'resourceId'   => $getAttributeValue($resource, ResourceIdentifier::RESOURCE_ID),
            'resourceType' => $getAttributeValue($resource, ResourceIdentifier::RESOURCE_TYPE),
        ];

        $retrievedData = $this->repository->find($requestData);
        foreach ($pepRequest->getRequestAttributes() as $requestAttribute) {
            if ($requestAttribute->getCategory() === ContextSelectorIdentifier::CONTENT_SELECTOR) {
                continue;
            }
            $requestAttribute->addContent(ContextSelectorIdentifier::CONTENT_SELECTOR, new Content($retrievedData));
        }
    }
}
