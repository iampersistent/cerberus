<?php

use Cerberus\PDP\CerberusEngine;
use Cerberus\PEP\PepAgentFactory;
use Cerberus\PEP\PepConfig;

class PepAgentFactoryCest
{
    public function testConstruct(UnitTester $I)
    {
        $data = [
            'factories' => [

            ],
        ];
        $factory = new PepAgentFactory($properties);

        $pepAgent = $factory->getPepAgent();

        $I->assertInstanceOf(CerberusEngine::class, $pepAgent->getPdpEngine());
        $I->assertInstanceOf(PepConfig::class, $pepAgent->getPepConfig());
    }
}