<?php
declare(strict_types = 1);

namespace Test\Functional\Pip;

use FunctionalTester;
use Cerberus\PEP\Action\ReadAction;
use Cerberus\PEP\ResourceObject;
use Cerberus\PEP\Subject;
use Cerberus\PIP\Permission\PermissionManager;
use Cerberus\PIP\Permission\PermissionMemoryRepository;

class PermissionManagerCest
{
    /** @var PermissionManager */
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
        $this->permissionManager->grant($subject, new ReadAction(), $resource);

        $record = $this->permissionManager->find($subject, $resource);

        $I->assertTrue($record->hasAction(new ReadAction()));
    }

    public function testDenyPermission(FunctionalTester $I)
    {
        $subject = new Subject('id101');
        $resource = new ResourceObject('test', 'testId101');

        // set permission
        $this->permissionManager->deny($subject, new ReadAction(), $resource);

        $record = $this->permissionManager->find($subject, $resource);

        $I->assertFalse($record->hasAction(new ReadAction()));
        $I->assertNotContains('read', $record->getActions());
    }
}