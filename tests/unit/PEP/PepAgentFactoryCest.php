<?php

use Cerberus\PDP\CerberusEngine;
use Cerberus\PDP\Utility\ArrayProperties;
use Cerberus\PEP\PepAgentFactory;
use Cerberus\PEP\PepConfig;

class PepAgentFactoryCest
{
    public function testConstruct(UnitTester $I)
    {
        require __DIR__ . '/../../_data/fixtures/PEP/testMapperProperties.php';
        $properties = new ArrayProperties($testMapperProperties);
        $factory = new PepAgentFactory($properties);

        $pepAgent = $factory->getPepAgent();

        $I->assertInstanceOf(CerberusEngine::class, $pepAgent->getPdpEngine());
        $I->assertInstanceOf(PepConfig::class, $pepAgent->getPepConfig());
    }
}