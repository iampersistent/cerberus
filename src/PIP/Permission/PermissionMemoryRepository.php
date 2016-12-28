<?php
declare(strict_types = 1);

namespace Cerberus\PIP\Permission;

use Cerberus\PIP\Contract\PermissionRepository;
use PDO;

class PermissionMemoryRepository implements PermissionRepository
{
    protected static $store = [];

    public function __construct(array $config)
    {
    }

    public function find($inputs)
    {
        foreach (self::$store as $data) {
            if (
                $data['subject']['id'] === $inputs['subjectId'] &&
                $data['subject']['type'] === $inputs['subjectType'] &&
                $data['resource']['id'] === $inputs['resourceId'] &&
                $data['resource']['type'] === $inputs['resourceType']
            ) {
                return $data;
            }
        }

        return null;
    }

    public function save($record)
    {
        if (! isset($record['id'])) {
            $record['id'] = uniqid('memoryRepo', true);
        }

        self::$store[$record['id']] = $record;
    }
}