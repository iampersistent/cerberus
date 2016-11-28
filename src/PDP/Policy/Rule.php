<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\PDP\Contract\Matchable;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\MatchCode;
use Cerberus\PDP\Evaluation\MatchResult;
use Cerberus\PDP\Policy\Traits\PolicyComponent;

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
}