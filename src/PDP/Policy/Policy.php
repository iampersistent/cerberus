<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Decision;
use Cerberus\PDP\Combiner\CombiningAlgorithm;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\EvaluationResult;
use Cerberus\PDP\Evaluation\MatchCode;
use Cerberus\PDP\Evaluation\MatchResult;
use Cerberus\PDP\Exception\EvaluationException;
use Ds\Set;

class Policy extends PolicyDef
{
    /** @var CombiningElement[]|Set */
    protected $combiningRules;

    /** @var TargetedCombinerParameterMap */
    protected $ruleCombinerParameters;
    protected $variableMap;

    public function __construct()
    {
        parent::__construct();
        $this->ruleCombinerParameters = new TargetedCombinerParameterMap();
        $this->variableMap = new VariableMap();
    }

    public function evaluate(EvaluationContext $evaluationContext): EvaluationResult
    {
        /** @var MatchResult $matchResult */
        $matchResult = $this->match($evaluationContext);

        switch ($matchResult->getMatchCode()->getValue()) {
            case MatchCode::INDETERMINATE:
                return new EvaluationResult(Decision::INDETERMINATE(), $matchResult->getStatus());
            case MatchCode::MATCH:
                break;
            case MatchCode::NO_MATCH:
                return new EvaluationResult(Decision::NOT_APPLICABLE());
        }

        $ruleCombiningElements = $this->getCombiningRules();

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
        if ($evaluationContext->getRequest()->shouldReturnPolicyIdList()) {
            $evaluationResultCombined->addPolicyIdentifier($this->getIdentifier());
        }

        $decision = $evaluationResultCombined->getDecision();
        if ($decision === Decision::DENY() || $decision === Decision::PERMIT()
        ) {
            $this->updateResult($evaluationResultCombined, $evaluationContext);
        }

        return $evaluationResultCombined;
    }

    public function getRuleCombiningAlgorithm(): CombiningAlgorithm
    {
        return $this->combiningAlgorithm;
    }

//    public function setRuleCombiningAlgorithm($combiningAlgorithm): self
//    {
//        $this->combiningAlgorithm = $combiningAlgorithm;
//
//        return $this;
//    }

    /**
     * @param $variableId
     *
     * @return VariableDefinition|null
     */
    public function getVariableDefinition($variableId)
    {
        return $this->variableMap->getVariableDefinition($variableId);
    }

    public function addVariableDefinition(VariableDefinition $variableDefinition): self
    {
        $this->variableMap->add($variableDefinition);

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
