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
                __DIR__ . '/../../_data/fixtures/testPolicy.php',
                __DIR__ . '/../../_data/fixtures/Examples/GalleryImage/galleryPolicy.php',
            ],
        ];
        $properties = new ArrayProperties($data);
        $factory = new ArrayPolicyFinderFactory();
        $finder = $factory->getPolicyFinder($properties);
        $policyDef = $finder->getPolicy('test001:policy');
        $I->assertInstanceOf(Policy::class, $policyDef);
        $I->assertEquals('test001:policy', $policyDef->getIdentifier());

        $policyDef = $finder->getPolicy('gallery-images:policy');
        $I->assertInstanceOf(Policy::class, $policyDef);
        $I->assertEquals('gallery-images:policy', $policyDef->getIdentifier());
    }
}