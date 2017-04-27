<?php
declare(strict_types = 1);

namespace Test\Unit\PEP\Policies;

use UnitTester;
use Cerberus\PEP\Action\ReadAction;
use TestData\{
    ChildObject, PEP\Mappers\ChildObjectMapper
};

class TargetByResourceTypeCest extends MatchBaseCest
{
    protected $policyPath = 'target-by-resource-type-policy';

    protected $configurationMappers = [
        'documentMapper',
        ChildObjectMapper::class,
    ];

    public function testTargetWithChildObject(UnitTester $I)
    {
        $child = new ChildObject(24);

        $response = $this->pepAgent->decide($this->subject, new ReadAction(), $child);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    public function testTargetWithDocument(UnitTester $I)
    {
        $response = $this->pepAgent->decide($this->subject, new ReadAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());
    }
}