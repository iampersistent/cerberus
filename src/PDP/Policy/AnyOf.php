<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;
use Cerberus\PDP\Contract\Matchable;
use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\MatchCode;
use Cerberus\PDP\Evaluation\MatchResult;
use Cerberus\PDP\Policy\Traits\PolicyComponent;

class AnyOf implements Matchable, PolicyElement
{
    use PolicyComponent;

    /** @var AllOf[] */
    protected $allOfs = [];

    public function __construct($allOfs = [])
    {
        foreach ($allOfs as $allOf) {
            $this->allOfs[] = new AllOf($allOf);
        }
    }

    public function match(EvaluationContext $evaluationContext): MatchResult
    {
        if (! $this->validate()) {
            return MatchResult::createIndeterminate($this->getStatus());
        }

        // Assume "No Match" until we find a match or an indeterminate result
        $resultFallThrough = MatchResult::createNoMatch();
        foreach ($this->allOfs as $allOf) {
            $matchResultAllOf = $allOf->match($evaluationContext);
            switch ($matchResultAllOf->getMatchCode()->getValue()) {
                case MatchCode::INDETERMINATE:
                    // Keep the first indeterminate value to return if no other match is found
                    if (! $resultFallThrough->getMatchCode()->is(MatchCode::INDETERMINATE )) {
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

    protected function validateComponent(): bool
    {
        if (empty($this->allOfs)) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), 'Missing AllOf elements in AnyOf');

            return false;
        } else {
            $this->setStatus(StatusCode::STATUS_CODE_OK());

            return true;
        }
    }
}
