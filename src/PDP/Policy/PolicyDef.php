<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\{
    Decision, Status, StatusCode
};
use Cerberus\PDP\Evaluation\{
    EvaluationContext, EvaluationResult, MatchCode, MatchResult
};
use Cerberus\PDP\Exception\EvaluationException;
use Cerberus\PDP\Policy\Traits\PolicyComponent;
use Ds\Set;

class PolicyDef extends PolicySetChild
{
    use PolicyComponent;

    protected $adviceExpressions;
    /** @var CombinerParameter[]|Set */
    protected $combinerParameters;
    /** @var CombiningAlgorithm */
    protected $combiningAlgorithm;
    protected $identifier;
    protected $obligationExpressions;
    /** @var Rule[] */
    protected $rules;
    /** @var Target */
    protected $target;

    public function __construct()
    {
        $this->combinerParameters = new Set();
        $this->rules = new Set();
        parent::__construct();
    }

    public function getAdviceExpressions()
    {
        return $this->adviceExpressions;
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

    public function getObligationExpressions()
    {
        return $this->obligationExpressions;
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
        $this->rules->add($rule);
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

    protected function updateResult(EvaluationResult $evaluationResult, EvaluationContext $evaluationContext)
    {
        $obligationExpressions = $this->getObligationExpressions();
        if ($obligationExpressions && ! $obligationExpressions->isEmpty()) {
            $listObligations = ObligationExpression::evaluate($evaluationContext,
                $this->getPolicyDefaults(),
                $evaluationResult->getDecision(),
                $obligationExpressions);
            if ($listObligations && ! $listObligations->isEmpty()) {
                $evaluationResult->addObligations($listObligations);
            }
        }

        $adviceExpressions = $this->getAdviceExpressions();
        if ($adviceExpressions && ! $adviceExpressions->isEmpty()) {
            $advices = AdviceExpression::evaluate($evaluationContext, $this->getPolicyDefaults(),
                $evaluationResult->getDecision(),
                $adviceExpressions);
            if ($advices && ! $advices->isEmpty()) {
                $evaluationResult->addAdvice($advices);
            }
        }
    }
    
    protected function validateComponent(): bool
    {
        // todo: OpenAZ did a check for a version being present here, example files don't necessarily have it
        if (! $this->getIdentifier()) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), "Missing identifier");

            return false;
        }
        if (! $this->getTarget()) {
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