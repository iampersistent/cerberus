<?php
declare(strict_types = 1);

namespace Cerberus\PIP\Permission;

use Cerberus\PIP\Contract\PermissionRepository;

class PermissionMemoryRepository implements PermissionRepository
{
    /** @var MappedObject[] */
    protected static $store = [];

    public function __construct(array $config)
    {
    }

    public function find($id)
    {
        return self::$store[$id] ?? null;
    }

    public function findByIdentifiers(array $inputs)
    {
        foreach (self::$store as $data) {
            if (
                $data->getSubjectId() === $inputs['subjectId'] &&
                $data->getSubjectType() === $inputs['subjectType'] &&
                $data->getResourceId() === $inputs['resourceId'] &&
                $data->getResourceType() === $inputs['resourceType']
            ) {
                return $data;
            }
        }

        return null;
    }

    public function save(MappedObject $object)
    {
        if (! $object->getId()) {
            $object->setId(uniqid('memoryRepo', true));
        }

        self::$store[$object->getId()] = $object;
    }
}