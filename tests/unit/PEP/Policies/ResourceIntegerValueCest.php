<?php
declare(strict_types = 1);

namespace Test\Unit\PEP\Policies;

use Cerberus\PEP\Action\WriteAction;
use UnitTester;

class ResourceIntegerValueCest extends MatchBaseCest
{
    protected $policyPath = 'resource-integer-value-equals-policy';
    protected $configurationMappers = [
        'documentMapper.php',
    ];

    public function testGetResourceValueFalse(UnitTester $I)
    {
        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertFalse($response->allowed());
    }

    public function testGetResourceValueTrue(UnitTester $I)
    {
        $this->document->setDocumentSize(123456);
        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());
    }
}