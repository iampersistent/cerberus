<?php
declare(strict_types = 1);

namespace Cerberus\PIP\Permission;

use Cerberus\PEP\Action\Action;
use Cerberus\PEP\ResourceObject;
use Cerberus\PEP\Subject;
use Cerberus\PIP\Contract\PermissionRepository;

class PermissionManager
{
    protected $repository;

    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function find(Subject $subject, ResourceObject $resource)
    {
        $inputs = $this->createRequestData($subject, $resource);

        return $this->repository->find($inputs);
    }

    public function deny(Subject $subject, Action $action, ResourceObject $resource, $properties = [])
    {
        $requestData = $this->createRequestData($subject, $resource);
        if (!$record = $this->repository->find($requestData)) {
            return;
        }
        $actions = $record['actions'];
        $deniedAction = $action->getAttribute('action:action-id');
        if (false === $key = array_search($deniedAction, $actions)) {
            return;
        }
        unset($actions[$key]);
        $record['actions'] = $actions;

        $this->repository->save($record);
    }

    public function grant(Subject $subject, Action $action, ResourceObject $resource, $properties = [])
    {
        $requestData = $this->createRequestData($subject, $resource);
        $record = $this->repository->find($requestData) ?? [
                'resource' => [
                    'id'   => $requestData['resourceId'],
                    'type' => $requestData['resourceType'],
                ],
                'subject'  => [
                    'id'   => $requestData['subjectId'],
                    'type' => $requestData['subjectType'],
                ],
                'actions'  => [],
            ];
        $actions = $record['actions'];
        $grantedAction = $action->getAttribute('action:action-id');

        if (! in_array($grantedAction, $actions)) {
            $actions[] = $grantedAction;
        }
        $record['actions'] = $actions;

        $this->repository->save($record);
    }

    protected function createRequestData(Subject $subject, ResourceObject $resource)
    {
        return [
            'subjectId'    => $subject->getAttribute('subject:subject-id'),
            'subjectType'  => $subject->getAttribute('subject:subject-type'),
            'resourceId'   => $resource->getAttribute('resource:resource-id'),
            'resourceType' => $resource->getAttribute('resource:resource-type'),
        ];
    }
}