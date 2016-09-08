<?php
declare(strict_types = 1);

use AspectMock\Test as Mock;
use Codeception\Util\Stub;
use Cerberus\Core\{
    Decision, Request, Status, StatusCode
};
use Cerberus\PDP\Combiner\DenyOverrides;
use Cerberus\PDP\Evaluation\{
    EvaluationContext, EvaluationResult, MatchCode, MatchResult
};

use Cerberus\PDP\Policy\{
    Policy, PolicyDef, Target
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

        $I->assertEquals(Decision::INDETERMINATE, $evaluationResult->getDecision());
    }

    protected function createPolicy(): PolicyDef
    {
        $policy = new Policy(new Status(StatusCode::STATUS_CODE_OK()));
// CombiningAlgorithmBase<Rule> ruleCombiningAlgorithm = new CombiningAlgorithmBase<Rule>(XACML1.ID_RULE_COMBINING_ALGORITHM) {
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