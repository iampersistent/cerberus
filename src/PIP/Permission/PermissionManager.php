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

    public function grant(Subject $subject, Action $action, $resource, $properties = [])
    {

        $this->repository->saveAttributes($attributes);
    }

    public function getRequestAttributes(string $category = null): Set
    {
        $this->
    }
}