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

    /** @var CombiningAlgorithm */
    protected $combiningAlgorithm;

    /** @var CombiningElement[] */
    protected $combiningRules;

    /** @var TargetedCombinerParameterMap */
    protected $ruleCombinerParameters;

    /** @var Target */
    protected $target;


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

    public function getTarget(): Target
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
        // todo: OpenAZ did a check for a version being present here, not sure why, example files don't necessarily have it
        if (false === parent::validateComponent()) {
            return false;
        }
        if ($this->getTarget() == null) {
            $this->setStatus(
                StatusCode::STATUS_CODE_SYNTAX_ERROR(),
                "Missing Target in policy " . $this->getIdReference()
            );

            return false;
        }

        return true;
    }
}