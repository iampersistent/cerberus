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
        $this->connection = new PDO($config['dsn'], $config['username'], $config['password'], $options);
    }

    public function find($inputs)
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

        if ($record = $statement->fetch(PDO::FETCH_ASSOC)) {
            $data = $this->convertDataFromDB($record);
        } else {
            $data = [];
        }

        return $data;
    }

    public function save($record)
    {
        $record['actions'] = json_encode($record['actions']);

        if (isset($record['id'])) {
            $sql = <<<SQL
UPDATE cerberus_mapped_object
SET allowed_actions = :actions 
WHERE id = :id
SQL;
            $parameters = [
                ':actions'  => $record['actions'],
                ':id'  => $record['id'],
            ];
        } else {
            $sql = <<<SQL
INSERT INTO cerberus_mapped_object
(subject_type, subject_id, resource_type, resource_id, allowed_actions) 
VALUES(:subjectType, :subjectId, :resourceType, :resourceId, :actions) 
SQL;
            $parameters = [
                ':subjectType'  => $record['subject']['type'],
                ':subjectId'  => $record['subject']['id'],
                ':resourceType'  => $record['resource']['type'],
                ':resourceId'  => $record['resource']['id'],
                ':actions'  => $record['actions'],
            ];
        }

        $statement = $this->connection->prepare($sql);
        $statement->execute($parameters);
    }

    protected function convertDataFromDB(array $data)
    {
        return [
            'resource' => [
                'id' => $data['resource_id'],
                'type' => $data['resource_type'],
            ],
            'subject' => [
                'id' => $data['subject_id'],
                'type' => $data['subject_type'],
            ],
            'actions' => json_decode($data['allowed_actions']),
        ];
    }
}