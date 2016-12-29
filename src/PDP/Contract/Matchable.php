<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Contract;

use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\MatchResult;

interface Matchable
{
    public function match(EvaluationContext $evaluationContext): MatchResult;
}