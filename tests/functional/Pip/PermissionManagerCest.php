<?php
declare(strict_types = 1);

use Cerberus\PEP\Action;
use Cerberus\PEP\ResourceObject;
use Cerberus\PEP\Subject;
use Cerberus\PIP\Permission\PermissionManager;
use Cerberus\PIP\Permission\PermissionMemoryRepository;

class PermissionManagerCest
{
    protected $permissionManager;

    public function _before(FunctionalTester $I)
    {
        $this->permissionManager = new PermissionManager(new PermissionMemoryRepository([]));
    }

    public function testGrantPermission(FunctionalTester $I)
    {
        $subject = new Subject('id101');
        $resource = new ResourceObject('test', 'testId101');

        // set permission
        $this->permissionManager->grant($subject, new Action('read'), $resource);

        $record = $this->permissionManager->find($subject, $resource);

        $I->assertTrue(in_array('read', $record['actions']));
    }

    public function testDenyPermission(FunctionalTester $I)
    {
        $subject = new Subject('id101');
        $resource = new ResourceObject('test', 'testId101');

        // set permission
        $this->permissionManager->deny($subject, new Action('read'), $resource);

        $record = $this->permissionManager->find($subject, $resource);

        $I->assertFalse(in_array('read', $record['actions']));
        $I->assertNotContains('read', $record['actions']);
    }
}