<?php
declare(strict_types = 1);

namespace Cerberus\PIP\Permission;

use Cerberus\PIP\Contract\PermissionRepository;
use PDO;

class PermissionMySqlRepository implements PermissionRepository
{
    /** @var PDO */
    protected $connection;

    public function __construct(array $config)
    {
        $options = $config['options'] ?? [];

        // don't rely on programmer to figure out dsn
        if (! isset($config['dsn']) && isset($config['host'], $config['database'])) {
            $config['dsn'] = "mysql:dbname={$config['database']};host={$config['host']}";
        }

        $this->connection = new PDO($config['dsn'], $config['username'], $config['password'], $options);
    }

    public function find($id)
    {
        $sql = <<<SQL
SELECT * FROM cerberus_mapped_object 
WHERE id = :id
SQL;
        $parameters = [
            ':id' => $id,
        ];
        $statement = $this->connection->prepare($sql);
        $statement->execute($parameters);
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        return $data ? new MappedObject($data) : null;
    }

    public function findByIdentifiers(array $inputs)
    {
        $sql = <<<SQL
SELECT * FROM cerberus_mapped_object 
WHERE subject_type = :subjectType
AND subject_id = :subjectId
AND resource_type = :resourceType
AND resource_id = :resourceId
SQL;
        $parameters = [
            ':subjectType'  => $inputs['subjectType'],
            ':subjectId'    => $inputs['subjectId'],
            ':resourceType' => $inputs['resourceType'],
            ':resourceId'   => $inputs['resourceId'],
        ];
        $statement = $this->connection->prepare($sql);
        $statement->execute($parameters);
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        return $data ? new MappedObject($data) : null;
    }

    public function save(MappedObject $object)
    {
        if ($object->getId()) {
            $sql = <<<SQL
UPDATE cerberus_mapped_object
SET allowed_actions = :actions 
WHERE id = :id
SQL;
            $parameters = [
                ':id'      => $object->getId(),
                ':actions' => json_encode($object->getActions()),
            ];
        } else {
            $sql = <<<SQL
INSERT INTO cerberus_mapped_object
(subject_type, subject_id, resource_type, resource_id, allowed_actions) 
VALUES(:subjectType, :subjectId, :resourceType, :resourceId, :actions) 
SQL;
            $parameters = [
                ':subjectId'    => $object->getSubjectId(),
                ':subjectType'  => $object->getSubjectType(),
                ':resourceId'   => $object->getResourceId(),
                ':resourceType' => $object->getResourceType(),
                ':actions'      => json_encode($object->getActions()),
            ];
        }

        $statement = $this->connection->prepare($sql);
        $statement->execute($parameters);
    }
}