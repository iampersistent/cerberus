<?php
declare(strict_types = 1);

use Codeception\Util\Stub;
use Cerberus\Core\{
    Decision, Request, Status, StatusCode
};
use Cerberus\PDP\Combiner\DenyOverrides;
use Cerberus\PDP\Evaluation\{
    EvaluationContext, EvaluationResult, MatchCode, MatchResult
};

use Cerberus\PDP\Policy\{
    Policy, Target
};

class PolicyCest
{
    public function testIncludePolicyIdentifierForIndeterminate(UnitTester $I)
    {
        $policy = $this->createPolicy();

        $evaluationContext = Stub::make(
            EvaluationContext::class,
            [
                'getRequest' => Stub::make(
                    Request::class,
                    [
                        'getReturnPolicyIdList' => true,
                    ]
                ),
            ]
        );
        $evaluationResult = $policy->evaluate($evaluationContext);

        $I->assertEquals(Decision::INDETERMINATE(), $evaluationResult->getDecision());

        $policyIdentifiers = $evaluationResult->getPolicyIdentifiers();

        $I->assertEquals(1, $policyIdentifiers->count());
    }

    protected function createPolicy(): Policy
    {
        $policy = new Policy(new Status(StatusCode::STATUS_CODE_OK()));
        $combiningAlgorithm = Stub::make(
            DenyOverrides::class, [
            'combine' => new EvaluationResult(Decision::INDETERMINATE()),
        ]);
        $target = Stub::make(
            Target::class,
            [
                'match' => new MatchResult(MatchCode::MATCH()),
            ]
        );

        $policy
            ->setIdentifier(42)
            ->setRuleCombiningAlgorithm($combiningAlgorithm)
            ->setTarget($target);

        return $policy;
    }


}