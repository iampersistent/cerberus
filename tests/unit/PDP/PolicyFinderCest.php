<?php
declare(strict_types = 1);

use Cerberus\PDP\Policy\PolicyFinder;
use Cerberus\PDP\Exception\PolicyFinderException;
use Ds\Set;

class PolicyFinderCest
{
    public function testMissingPolicyId(UnitTester $I)
    {
        $I->expectException(new PolicyFinderException('No matching policy found'), function() {
            $policyFinder = new PolicyFinder(new Set(), new Set());
            $policyFinder->getPolicy('notHere');
        });
    }
}