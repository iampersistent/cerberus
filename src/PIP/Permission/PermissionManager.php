<?php
declare(strict_types = 1);

namespace Cerberus\PIP\Permission;

use Cerberus\PEP\Action;
use Cerberus\PEP\Subject;
use Cerberus\PIP\Contract\PermissionRepository;
use Ds\Set;

class PermissionManager
{
    protected $repository;

    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function grant(Subject $subject, Action $action, Resource $resource, $properties = [])
    {
        $requestData = PermissionManager::createRequestData($subject, $resource);
        $record = $this->repository->find($requestData) ?? [
            'resource' => [
                'id' => $requestData['resourceId'],
                'type' => $requestData['resourceType'],
            ],
                'subject' => [
                    'id' => $requestData['subjectId'],
                    'type' => $requestData['subjectType'],
                ],
                'actions' => [],
            ];
        $actions = $record['action'];
        $newAction = self::getAttributeValue($action, 'action:action_id');

        if (!in_array($newAction, $actions)) {
            $actions[] = $newAction;
        }
        $record['action'] = $actions;

        $this->repository->save($record);
    }

    public function getRequestAttributes(string $category = null): Set
    {
    }

    public static function createRequestData(Subject $subject, Resource $resource)
    {
        return [
            'subjectId'    => self::getAttributeValue($subject, 'subject:subject-id'),
            'subjectType'    => self::getAttributeValue($subject, 'subject:subject-type'),
            'resourceId'    => self::getAttributeValue($resource, 'resource:resource-id'),
            'resourceType'    => self::getAttributeValue($resource, 'resource:resource-type'),
        ];
    }
    
    protected static function getAttributeValue($attribute, $attributeId)
    {
        return $attribute->getAttribute($attributeId)->getValues()->first()->getValue();
    }
}