<?php

use Cerberus\PDP\CerberusEngineFactory;
use Cerberus\PDP\Utility\ArrayProperties;

class ArrayPropertiesCest
{
    /** @var \Cerberus\PDP\Utility\Properties */
    protected $properties;
    /** @var string */
    protected $rootPolicies;

    public function _before(UnitTester $I)
    {
        $this->rootPolicies = [
            __DIR__ . '/../../../_data/fixtures/test.php',
        ];
        $properties = [
            'factories' => [
                'pdpEngine' => CerberusEngineFactory::class
            ],
            'rootPolicies' => $this->rootPolicies,
        ];
        $this->properties = new ArrayProperties($properties);
    }

    public function testConstructFactories(UnitTester $I)
    {
        $I->assertSame(CerberusEngineFactory::class, $this->properties->get('factory.pdpEngine'));
    }

    public function testConstructRootPolicies(UnitTester $I)
    {
        $I->assertSame($this->rootPolicies, $this->properties->get('rootPolicies'));
    }
}