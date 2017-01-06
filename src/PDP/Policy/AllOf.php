<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\AttributeValue;
use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Contract\Matchable;
use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Evaluation\{
    EvaluationContext, MatchCode, MatchResult
};
use Cerberus\PDP\Policy\Expressions\AttributeDesignator;
use Cerberus\PDP\Policy\Traits\PolicyComponent;
use Ds\Set;

class AllOf implements Matchable, PolicyElement
{
    use PolicyComponent;

    /** @var Match[]|Set */
    protected $matches;

    public function __construct()
    {
        $this->matches = new Set();
    }

    public function addMatch(Match $match): self
    {
        $this->matches->add($match);

        return $this;
    }

    public function match(EvaluationContext $evaluationContext): MatchResult
    {
        if (! $this->validate()) {
            return MatchResult::createIndeterminate($this->getStatus());
        }

        $matchResultFallThrough = MatchResult::createMatch();
        foreach ($this->matches as $match) {
            $matchResult = $match->match($evaluationContext);
            switch ($matchResult->getMatchCode()->getValue()) {
                case MatchCode::INDETERMINATE:
                    if (! $matchResultFallThrough->getMatchCode()->is(MatchCode::INDETERMINATE)) {
                        $matchResultFallThrough = $matchResult;
                    }
                    break;
                case MatchCode::MATCH:
                    break;
                case MatchCode::NO_MATCH:
                    return $matchResult;
            }
        }

        return $matchResultFallThrough;
    }

    protected function validateComponent(): bool
    {
        if (0 === count($this->matches)) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Missing matches');

            return false;
        } else {
            $this->setStatus(StatusCode::STATUS_CODE_OK());

            return true;
        }
    }

}
