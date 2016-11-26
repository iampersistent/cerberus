<?php

use Cerberus\PDP\CerberusEngineFactory;
use Cerberus\PDP\Utility\ArrayProperties;

class ArrayPropertiesCest
{
    /** @var \Cerberus\PDP\Utility\Properties */
    protected $properties;

    public function _before(UnitTester $I)
    {
        $properties = [
            'factories' => [
                'pdpEngine' => CerberusEngineFactory::class
            ],
        ];
        $this->properties = new ArrayProperties($properties);
    }

    public function testConstructFactories(UnitTester $I)
    {
        $I->assertSame(CerberusEngineFactory::class, $this->properties->get('factory.pdpEngine'));
    }
}