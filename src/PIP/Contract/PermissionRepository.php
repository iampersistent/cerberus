<?php
declare(strict_types = 1);

namespace Cerberus\PIP\Contract;

use Cerberus\PIP\Permission\MappedObject;

interface PermissionRepository
{
    public function find($id);
    public function findByIdentifiers(array $inputs);
    public function save(MappedObject $record);
}