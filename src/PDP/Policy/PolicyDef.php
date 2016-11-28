<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\{
    Decision, Status, StatusCode
};
use Cerberus\PDP\Evaluation\{
    EvaluationContext, MatchCode, MatchResult
};
use Cerberus\PDP\Exception\EvaluationException;
use Cerberus\PDP\Policy\Traits\PolicyComponent;
use Ds\Set;

class PolicyDef extends PolicySetChild
{
    use PolicyComponent;

    /** @var CombinerParameter[]|Set */
    protected $combinerParameters;

    /** @var CombiningAlgorithm */
    protected $combiningAlgorithm;

    /** @var CombiningElement[] */
    protected $combiningRules;
    protected $identifier;

    /** @var TargetedCombinerParameterMap */
    protected $ruleCombinerParameters;
    /** @var Rule[] */
    protected $rules = [];
    /** @var Target */
    protected $target;

    public function __construct()
    {
        $this->combinerParameters = new Set();
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function setRuleCombiningAlgorithm($combiningAlgorithm): self
    {
        $this->combiningAlgorithm = $combiningAlgorithm;

        return $this;
    }

    public function getCombinerParameterList()
    {
        return $this->combinerParameters;
    }

    /**
     * @throws EvaluationException
     */
    public function match(EvaluationContext $evaluationContext): MatchResult
    {
        if (! $this->validate()) {
            return new MatchResult(MatchCode::INDETERMINATE(),
                new Status($this->getStatusCode(), $this->getStatusMessage()));
        }

        return $this->target->match($evaluationContext);
    }

    public function addRule(Rule $rule)
    {
        $this->rule[] = $rule;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setTarget(Target $target): self
    {
        $this->target = $target;

        return $this;
    }

    protected function validateComponent(): bool
    {
        // todo: OpenAZ did a check for a version being present here, example files don't necessarily have it
        if ($this->getIdentifier() == null) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), "Missing identifier");

            return false;
        }
        if (null === $this->getTarget()) {
            $this->setStatus(
                StatusCode::STATUS_CODE_SYNTAX_ERROR(),
                "Missing Target in policy " . $this->getIdentifier()
            );

            return false;
        }
        $this->setStatus(StatusCode::STATUS_CODE_OK());

        return true;
    }
}