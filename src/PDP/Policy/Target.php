<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Status;
use Cerberus\PDP\Contract\Matchable;
use Cerberus\PDP\Evaluation\{
    EvaluationContext, MatchCode, MatchResult
};
use Cerberus\PDP\Policy\Traits\PolicyComponent;

class Target implements Matchable
{
    use PolicyComponent;

    /** @var AnyOf[] */
    protected $anyOfs;

    public function match(EvaluationContext $evaluationContext): MatchResult
    {
        if (! $this->validate()) {
            return new MatchResult(MatchCode::INDETERMINATE(),
                new Status($this->getStatusCode(), $this->getStatusMessage()));
        }

        foreach ($this->anyOfs as $anyOf) {
            $matchResult = $anyOf->match($evaluationContext);
            if ($matchResult->getMatchCode() != MatchCode::MATCH) {
                return $matchResult;
            }
        }

        return new MatchResult(MatchCode::MATCH());
    }

    public function validate()
    {
        return true;
    }
}