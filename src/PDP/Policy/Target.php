<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Status;
use Cerberus\PDP\Contract\Matchable;
use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Evaluation\{
    EvaluationContext, MatchCode, MatchResult
};
use Cerberus\PDP\Policy\Traits\PolicyComponent;

class Target implements Matchable, PolicyElement
{
    use PolicyComponent;

    /** @var AnyOf[] */
    protected $anyOfs = [];

    public function match(EvaluationContext $evaluationContext): MatchResult
    {
        if (! $this->validate()) {
            return MatchResult::createIndeterminate($this->getStatus());
        }

        foreach ($this->anyOfs as $anyOf) {
            $matchResult = $anyOf->match($evaluationContext);
            if (!$matchResult->getMatchCode()->is(MatchCode::MATCH)) {
                return $matchResult;
            }
        }

        return MatchResult::createMatch();
    }

    public function addAnyOf(AnyOf $anyOf)
    {
        $this->anyOfs[] = $anyOf;
    }

    public function validate(): bool
    {
        // todo: add real code
        return true;
    }
}
