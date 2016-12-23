<?php
declare(strict_types = 1);

use Cerberus\PIP\Permission\PermissionManager;

class PermissionManagerCest
{
    public function testAddPermission(FunctionalTester $I)
    {
        $permissionManager = new PermissionManager($repo);

        // set permission
        $permissionManager->grant($user, $action, $resource, $properties);

        // look for permission
    }
}