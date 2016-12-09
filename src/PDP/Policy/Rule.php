<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Decision;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Contract\Matchable;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\EvaluationResult;
use Cerberus\PDP\Evaluation\MatchCode;
use Cerberus\PDP\Evaluation\MatchResult;
use Cerberus\PDP\Policy\Traits\PolicyComponent;
use Cerberus\Trace\TraceEvent;

class Rule implements Matchable
{
    use PolicyComponent;

    /** @var Condition */
    protected $condition;
    /** @var Policy */
    protected $policy;
    /** @var RuleEffect */
    protected $ruleEffect;
    /** @var string */
    protected $ruleId;
    /** @var Target */
    protected $target;

    public function __construct(PolicyDef $policy)
    {
        $this->policy = $policy;
    }

    public function evaluate(EvaluationContext $evaluationContext): EvaluationResult
    {
        if (! $this->validate()) {
            return new EvaluationResult(Decision::INDETERMINATE(), $this->getStatus());
        }

        /*
         * See if our target matches
         */
        $matchResult = $this->match($evaluationContext);

        switch ((string)$matchResult->getMatchCode()) {
            case MatchCode::INDETERMINATE:
                return new EvaluationResult(Decision::INDETERMINATE(), $matchResult->getStatus());
            case MatchCode::MATCH:
                break;
            case MatchCode::NO_MATCH:
                return new EvaluationResult(Decision::NOT_APPLICABLE());
        }

        /*
         * See if our condition matches
         */
        if ($thisCondition = $this->getCondition()) {
            $expressionResultCondition = $thisCondition->evaluate($evaluationContext,
                $this->getPolicy() . getPolicyDefaults());


            if (! $expressionResultCondition->isOk()) {
                return new EvaluationResult(Decision::INDETERMINATE(), $expressionResultCondition->getStatus());
            } else {
                if (! $expressionResultCondition->isTrue()) {
                    return new EvaluationResult(Decision::NOT_APPLICABLE());
                }
            }
        }

        /*
         * The target and condition match, so we can start creating the EvaluationResult
         */
//        List<Obligation > $listObligations = ObligationExpression->evaluate($evaluationContext, $this->getPolicy()->getPolicyDefaults(), $this->getRuleEffect()->getDecision(), $this->getObligationExpressionList());
//        List<Advice > $listAdvices = AdviceExpression->evaluate($evaluationContext, $this->getPolicy()
//           ->getPolicyDefaults(), $this->getRuleEffect()->getDecision(), $this->getAdviceExpressionList());

        return new EvaluationResult($this->getRuleEffect()->getDecision(), $listObligations, $listAdvices);
    }

    public function match(EvaluationContext $evaluationContext): MatchResult
    {
        if (! $this->validate()) {
            return new MatchResult(MatchCode::INDETERMINATE(), $this->getStatus());
        }

        if ($this->target) {
            return $this->target->match($evaluationContext);
        } else {
            return MatchResult::MATCH();
        }
    }

    public function getCondition(): Condition
    {
        return $this->condition;
    }

    public function setCondition(Condition $condition): self
    {
        $this->condition = $condition;

        return $this;
    }

    public function getPolicy(): Policy
    {
        return $this->policy;
    }

    public function getRuleEffect(): RuleEffect
    {
        return $this->ruleEffect;
    }

    public function setRuleEffect(RuleEffect $ruleEffect): self
    {
        $this->ruleEffect = $ruleEffect;

        return $this;
    }

    public function getRuleId(): string
    {
        return $this->ruleId;
    }

    public function setRuleId(string $ruleId): self
    {
        $this->ruleId = $ruleId;

        return $this;
    }

    public function setTarget(Target $target): self
    {
        $this->target = $target;

        return $this;
    }

    protected function validateComponent(): bool
    {
        if (! $this->getRuleId()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), "Missing rule id");

            return false;
        }
        if (! $this->getPolicy()) {
            $this->setStatus(StatusCode::STATUS_CODE_PROCESSING_ERROR(), "Rule not in a Policy");

            return false;
        }
        if (! $this->getRuleEffect()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), "Missing effect");

            return false;
        }

        return true;
    }
}