<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Exception\IllegalArgumentException;
use Cerberus\Core\Identifier;
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
        $subject = $pepRequest->getPepRequestAttributes(Identifier::SUBJECT_CATEGORY_ACCESS_SUBJECT);
        $resource = $pepRequest->getPepRequestAttributes(Identifier::ATTRIBUTE_CATEGORY_RESOURCE);
        $getAttributeValue = function($attribute, $attributeId) {
            return $attribute->getAttribute($attributeId)->getValues()->first()->getValue();
        };
        $requestData = [
            'subjectId'    => $getAttributeValue($subject, 'subject:subject-id'),
            'subjectType'    => $getAttributeValue($subject, 'subject:subject-type'),
            'resourceId'    => $getAttributeValue($resource, 'resource:resource-id'),
            'resourceType'    => $getAttributeValue($resource, 'resource:resource-type'),
        ];

        $retrievedData = $this->repository->find($requestData);
        foreach ($pepRequest->getRequestAttributes() as $requestAttribute) {
            if ($requestAttribute->getCategory() === Identifier::CONTENT_SELECTOR) {
                continue;
            }
            $requestAttribute->addContent('content-selector', new Content($retrievedData));
        }
    }
}