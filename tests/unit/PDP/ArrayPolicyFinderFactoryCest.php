<?php
declare(strict_types = 1);

use Cerberus\PDP\ArrayPolicyFinderFactory;
use Cerberus\PDP\Policy\Policy;

class ArrayPolicyFinderFactoryCest
{
    public function testInit(UnitTester $I)
    {
        require __DIR__ . '/../../_data/fixtures/test.php';

        $factory = new ArrayPolicyFinderFactory();
        $finder = $factory->getPolicyFinder($policy);
        $policyDef = $finder->getPolicy('test001:policy');
        $I->assertInstanceOf(Policy::class, $policyDef);
    }

}