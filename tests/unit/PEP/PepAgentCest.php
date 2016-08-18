<?php
declare(strict_types = 1);

use AspectMock\Test as Mock;
use Cerberus\PEP\PepAgent;
use Cerberus\PEP\PepResponse;
use Cerberus\PDP\CerberusEngine;

class PepAgentCest
{
    public function testPermit(UnitTester $I)
    {
        $pnpEngine = new CerberusEngine();
//        $mock = Mock::double(
//            $pnpEngine,
//            [
//                'method' => true,
//            ]
//        );
        $agent = new PepAgent($pnpEngine);

        $response = $agent->simpleDecide("Julius Hibbert", "read", "http://medico.com/record/patient/BartSimpson");

        $I->assertInstanceOf(PepResponse::class, $response);
        $I->assertTrue(true, $response->allowed());
    }
}