<?php
declare(strict_types = 1);

namespace Cerberus\PIP\Permission;

use Cerberus\PEP\{Action\Action, ResourceObject, Subject};
use Cerberus\PIP\Contract\PermissionRepository;

class PermissionManager
{
    protected $repository;

    public function __construct(PermissionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Find mapped object by subject-resource mapping
     *
     * @param Subject        $subject
     * @param ResourceObject $resource
     *
     * @return MappedObject
     */
    public function find(Subject $subject, ResourceObject $resource)
    {
        $object = MappedObject::fromSubjectResource($subject, $resource);

        return $this->repository->findByIdentifiers($object->getIdentifiers());
    }

    /**
     * Remove action from subject-resource mapping
     *
     * @param Subject        $subject
     * @param Action         $action
     * @param ResourceObject $resource
     * @param array          $properties
     */
    public function deny(Subject $subject, Action $action, ResourceObject $resource, $properties = [])
    {
        if (! $record = $this->find($subject, $resource)) {
            return;
        }

        $this->repository->save($record->removeAction($action));
    }

    /**
     * Add action to subject-resource mapping
     *
     * @param Subject        $subject
     * @param Action         $action
     * @param ResourceObject $resource
     * @param array          $properties
     */
    public function grant(Subject $subject, Action $action, ResourceObject $resource, $properties = [])
    {
        $record = $this->find($subject, $resource) ?? MappedObject::fromSubjectResource($subject, $resource);;

        $this->repository->save($record->addAction($action));
    }
}