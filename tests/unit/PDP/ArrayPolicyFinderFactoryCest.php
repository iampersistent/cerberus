<?php
declare(strict_types = 1);

use Cerberus\PDP\ArrayPolicyFinderFactory;
use Cerberus\PDP\Policy\Policy;
use Cerberus\PDP\Utility\ArrayProperties;

class ArrayPolicyFinderFactoryCest
{
    public function testInit(UnitTester $I)
    {
        $data = [
            'rootPolicies' => [
                __DIR__ . '/../../_data/fixtures/test.php'
            ],
        ];
        $properties = new ArrayProperties($data);
        $factory = new ArrayPolicyFinderFactory();
        $finder = $factory->getPolicyFinder($properties);
        $policyDef = $finder->getPolicy('test001:policy');
        $I->assertInstanceOf(Policy::class, $policyDef);
    }
}