<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Contract\Matchable;
use Cerberus\PDP\Evaluation\{
    EvaluationContext, MatchCode, MatchResult
};
use Cerberus\PDP\Policy\Traits\PolicyComponent;

class AllOf implements Matchable
{
    use PolicyComponent;

    /** @var Match[] */
    protected $matches;

    public function match(EvaluationContext $evaluationContext): MatchResult
    {
        if (!$this->validate()) {
            return new MatchResult(MatchCode::INDETERMINATE(), new Status($this->getStatusCode(), $this->getStatusMessage()));
        }
        foreach ($this->matches as $match) {
            $matchResultMatch = $match->match($evaluationContext);
            switch ($matchResultMatch->getMatchCode()) {
                case INDETERMINATE:
                    if (matchResultFallThrough.getMatchCode() != MatchResult.MatchCode.INDETERMINATE) {
                        matchResultFallThrough = matchResultMatch;
                    }
                    break;
                case MATCH:
                    break;
                case NOMATCH:
                    return matchResultMatch;
            }
        }

        return MatchResult.MM_MATCH;
    }

    protected function validateComponent(): boolean
    {
        if (empty($this->matches)) {
$this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), "Missing matches");
return false;
} else {
    $this->setStatus(StatusCode::STATUS_CODE_OK());
    return true;
}
}

}