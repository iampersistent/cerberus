<?php
declare(strict_types = 1);

namespace Test\Unit\PEP\Policies;

use Cerberus\PEP\Action\WriteAction;
use UnitTester;

class ResourceStringValueCest extends MatchBaseCest
{
    protected $policyPath = 'resource-string-value-equals-policy';
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
        $this->document->setClientName('John Smith');
        $response = $this->pepAgent->decide($this->subject, new WriteAction(), $this->document);

        $I->assertNotNull($response);
        $I->assertTrue($response->allowed());
    }
}