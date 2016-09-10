<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Decision;
use Cerberus\Core\Status;
use Cerberus\PDP\Combiner\CombiningAlgorithm;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\EvaluationResult;
use Cerberus\PDP\Evaluation\MatchCode;
use Cerberus\PDP\Exception\EvaluationException;
use Ds\Set;

class Policy extends PolicyDef
{
    protected $rules;

    public function __construct()
    {
        parent::__construct();
        $this->rules = new Set();
    }

    public function evaluate(EvaluationContext $evaluationContext): EvaluationResult
    {
        /*
         * First check to see if we are valid. If not, return an error status immediately
         */
        if (! $this->validate()) {
            return new EvaluationResult(
                Decision::INDETERMINATE(),
                new Status($this->getStatusCode(), $this->getStatusMessage())
            );
        }

        /*
         * See if we match
         */
        /** @var MatchResult $matchResult */
        $matchResult = $this->match($evaluationContext);

//        if ($evaluationContext->isTracing()) {
//            $evaluationContext->trace(new StdTraceEvent < MatchResult>("Match", this, thisMatchResult));
//        }
        switch ($matchResult->getMatchCode()) {
            case MatchCode::INDETERMINATE:
                return new EvaluationResult(Decision::INDETERMINATE(), $matchResult->getStatus()); // todo: decision?
            case MatchCode::MATCH:
                break;
            case MatchCode::NO_MATCH:
                return new EvaluationResult(Decision::NOT_APPLICABLE()); // todo: decision?
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
        if (! $evaluationResultCombined = $this->getRuleCombiningAlgorithm()->combine(
            $evaluationContext,
            $ruleCombiningElements,
            $this->getCombinerParameterList()
            )
        ) {
            throw new EvaluationException();
        }

        /*
         * Add my id to the policy identifiers
         */
        if ($evaluationContext->getRequest()->getReturnPolicyIdList()) {
            $evaluationResultCombined->addPolicyIdentifier($this->getIdentifier());
        }

        $decision = $evaluationResultCombined->getDecision();
        if ($decision === Decision::DENY() || $decision === Decision::PERMIT()
        ) {
            $this->updateResult($evaluationResultCombined, $evaluationContext);
        }
//        if ($evaluationContext->isTracing()) {
//            $evaluationContext->trace(new StdTraceEvent<Result>("Result", $this, $evaluationResultCombined));
//        }

        return $evaluationResultCombined;
    }

    public function getRuleCombiningAlgorithm(): CombiningAlgorithm
    {
        return $this->combiningAlgorithm;
    }

    public function setRuleCombiningAlgorithm($combiningAlgorithm): self
    {
        $this->combiningAlgorithm = $combiningAlgorithm;

        return $this;
    }

    protected function getCombiningRules(): Set
    {
        if (null === $this->combiningRules) {
            $this->combiningRules = new Set();
            foreach ($this->rules as $rule) {
                $this->combiningRules->add(
                    new CombiningElement(
                        $rule,
                        $this->ruleCombinerParameters->getCombinerParameters($rule)
                    )
                );
            }
        }

        return $this->combiningRules;
    }
}