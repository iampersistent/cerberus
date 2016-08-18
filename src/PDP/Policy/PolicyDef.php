<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Decision;
use Cerberus\Core\Status;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\EvaluationResult;
use Cerberus\PDP\Evaluation\MatchCode;
use Cerberus\PDP\Evaluation\MatchResult;
use Cerberus\PDP\Exception\EvaluationException;
use Cerberus\PDP\Policy\Traits\PolicyComponent;
use Ds\Collection;

abstract class PolicyDef
{
    use PolicyComponent;

    /** @var CombiningAlgorithm */
    protected $combiningAlgorithm;

    /** @var CombiningElement[] */
    protected $combiningRules;

    /** @var TargetedCombinerParameterMap */
    protected $ruleCombinerParameters;

    /** @var Target */
    protected $target;


    public function evaluate(EvaluationContext $evaluationContext): EvaluationResult
    {
        /*
         * First check to see if we are valid. If not, return an error status immediately
         */
        if (! $this->validate()) {
            return new EvaluationResult(new Status($this->getStatusCode(), $this->getStatusMessage())); /// todo: decision?
        }

        /*
         * See if we match
         */
        /** @var MatchResult $matchResult */
        $matchResult = $this->match($evaluationContext);
        //    assert $matchResult != null;

//        if ($evaluationContext->isTracing()) {
//            $evaluationContext->trace(new StdTraceEvent < MatchResult>("Match", this, thisMatchResult));
//        }
        switch ($matchResult->getMatchCode()) {
            case MatchCode::INDETERMINATE:
                return new EvaluationResult(Decision::INDETERMINATE, $matchResult->getStatus()); // todo: decision?
            case MatchCode::MATCH:
                break;
            case MatchCode::NO_MATCH:
                return new EvaluationResult(Decision::NOT_APPLICABLE); // todo: decision?
        }

        /*
         * Get the combining elements
         */
        // List<CombiningElement < Rule >>
        $ruleCombiningElements = $this->getCombiningRules();
        // assert $ruleCombiningElements != null;

        /*
         * Run the combining algorithm
         */
        // assert $this->getRuleCombiningAlgorithm() != null;
        /** @var EvaluationResult $evaluationResultCombined */
        if (! $evaluationResultCombined = $this->getRuleCombiningAlgorithm()->combine($evaluationContext,
            $ruleCombiningElements, $this->getCombinerParameterList())
        ) {
            throw new EvaluationException();
        }

        /*
         * Add my id to the policy identifiers
         */
        if ($evaluationContext->getRequest()->getReturnPolicyIdList()) {
            $evaluationResultCombined->addPolicyIdentifier($this->getIdReference());
        }

        if ($evaluationResultCombined->getDecision() == Decision::DENY
            || $evaluationResultCombined->getDecision() == Decision::PERMIT
        ) {
            $this->updateResult($evaluationResultCombined, $evaluationContext);
        }
//        if ($evaluationContext->isTracing()) {
//            $evaluationContext->trace(new StdTraceEvent<Result>("Result", $this, $evaluationResultCombined));
//        }

        return $evaluationResultCombined;
    }

    /**
     * @throws EvaluationException
     */
    public function match(EvaluationContext $evaluationContext): MatchResult
    {
        if (! $this->validate()) {
            return new MatchResult(MatchCode::INDETERMINATE(), new Status($this->getStatusCode(), $this->getStatusMessage()));
        }

        return $this->target->match($evaluationContext);
    }

    /**
     * @returns CombiningElement<Rule>[]
     */
    protected function getCombiningRules():Collection
    {
        if (null === $this->combiningRules) {
            $this->combiningRules = new Collection();
            $rules = $this->getRules();
            while ($rule = $rules->next()) {
                $this->combiningRules->add(new CombiningElement($rule, $this->ruleCombinerParameters->getCombinerParameters($rule)));
}
        }

        return $this->combiningRules;
    }
}