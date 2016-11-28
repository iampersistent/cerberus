<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Status;
use Cerberus\PDP\Contract\Matchable;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\MatchCode;
use Cerberus\PDP\Evaluation\MatchResult;
use Cerberus\PDP\Policy\Traits\PolicyComponent;

class AnyOf implements Matchable
{
    use PolicyComponent;

    /** @var AllOf[] */
    protected $allOfs = [];

    public function __construct($data)
    {
        foreach ($data as $datum) {

        }
    }

    public function match(EvaluationContext $evaluationContext): MatchResult
    {
        if (!$this->validate()) {
            return new MatchResult(MatchCode::INDETERMINATE(), $this->getStatus());
        }

        // Assume "No Match" until we find a match or an indeterminate result
        $resultFallThrough = MatchResult::createNoMatch();
        foreach ($this->allOfs as $allOf) {
            $matchResultAllOf = $allOf->match($evaluationContext);
            switch ((string)$matchResultAllOf->getMatchCode()) {
                case MatchCode::INDETERMINATE:
                    // Keep the first indeterminate value to return if no other match is found
                    if (MatchCode::INDETERMINATE !== (string)$resultFallThrough->getMatchCode()) {
                        $resultFallThrough = $matchResultAllOf;
                    }
                    break;
                case MatchCode::MATCH:
                    return $matchResultAllOf;
                case MatchCode::NO_MATCH:
                    break;
            }
        }

        return $resultFallThrough;
    }
}