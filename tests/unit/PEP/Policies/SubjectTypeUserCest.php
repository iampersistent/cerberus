<?php
declare(strict_types = 1);

namespace Test\Unit\PEP\Policies;

use UnitTester;
use Cerberus\PEP\Action\WriteAction;
use Cerberus\PEP\Subject;

class SubjectTypeUserCest extends MatchBaseCest
{
    protected $policyPath = 'subject-type-user-policy';

    public function testSubjectTypeMatch(UnitTester $I)
    {
        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());
    }

    public function testSubjectTypeMisMatch(UnitTester $I)
    {
        $response = $this->pepAgent->decide(new Subject('5', 'address'), new WriteAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }
}