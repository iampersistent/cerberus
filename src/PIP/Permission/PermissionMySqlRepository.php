<?php
declare(strict_types = 1);

namespace Cerberus\PIP\Permission;

use Cerberus\PIP\Contract\PermissionRepository;
use PDO;

class PermissionMySqlRepository implements PermissionRepository
{
    protected $connection;

    public function __construct(array $config)
    {
        $this->connection = new PDO($config['dsn'], $config['username'], $config['password'], $config['options']);
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
        $query = $this->connection->prepare($sql);
        $query->execute($parameters);

        $data = $query->fetch(PDO::FETCH_ASSOC);

        return $data;
    }
}