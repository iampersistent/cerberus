<?php

use Cerberus\PDP\CerberusEngine;
use Cerberus\PDP\Utility\ArrayProperties;
use Cerberus\PEP\PepAgentFactory;
use Cerberus\PEP\PepConfig;

class PepAgentFactoryCest
{
    public function testConstruct(UnitTester $I)
    {
        $pepPath = codecept_data_dir('fixtures/PEP');
        $defaultPath = "$pepPath/defaultProperties.php";
        $defaults = require $defaultPath;
        $defaults['rootPolicies'][] = "$pepPath/testPolicy004.php";
        $defaults['pep']['mappers']['configurations'][] = "$pepPath/Mappers/documentMapper.php";

        $pepAgent = (new PepAgentFactory(new ArrayProperties($defaults)))->getPepAgent();

        $I->assertInstanceOf(CerberusEngine::class, $pepAgent->getPdpEngine());
        $I->assertInstanceOf(PepConfig::class, $pepAgent->getPepConfig());
    }
}