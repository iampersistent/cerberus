<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\PDP\Contract\Matchable;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\MatchResult;
use Cerberus\PDP\Policy\Traits\PolicyComponent;

class AnyOf implements Matchable
{
    use PolicyComponent;

    public function match(EvaluationContext $evaluationContext): MatchResult
    {
        // TODO: Implement match() method.
    }
}