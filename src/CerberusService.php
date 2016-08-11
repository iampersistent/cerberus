<?php
declare(strict_types = 1);

namespace Cerberus;

class CerberusService
{
    public function can($user, $action, $resource, $properties = []): bool
    {
        return true;
    }
}
