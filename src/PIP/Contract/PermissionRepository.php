<?php
declare(strict_types = 1);

namespace Cerberus\PIP\Contract;

interface PermissionRepository
{
    public function find($inputs);
    public function save($record);
}