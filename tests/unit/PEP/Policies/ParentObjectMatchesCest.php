<?php
declare(strict_types = 1);

namespace Test\Unit\PEP\Policies;

use UnitTester;
use Cerberus\PEP\Action\ReadAction;
use TestData\ChildObject;
use TestData\ParentObject;
use TestData\PEP\Mappers\ChildObjectMapper;

class ParentObjectMatchesCest extends MatchBaseCest
{
    protected $policyPath = 'parent-object-matches-policy';

    protected $configurationMappers = [
        ChildObjectMapper::class,
    ];

    public function testTestChildWithValidParent(UnitTester $I)
    {
        $child = new ChildObject(24);
        $parent = new ParentObject(1);
        $child->setParent($parent);

        $response = $this->pepAgent->decide($this->subject, new ReadAction(), $child);

        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());
    }

    public function testOrphanedChild(UnitTester $I)
    {
        $orphan = new ChildObject(-1);

        $response = $this->pepAgent->decide($this->subject, new ReadAction(), $orphan);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    public function testTestChildWithInvalidParent(UnitTester $I)
    {
        $child = new ChildObject(-1);
        $parent = new ParentObject(-1);
        $child->setParent($parent);

        $response = $this->pepAgent->decide($this->subject, new ReadAction(), $child);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }
}