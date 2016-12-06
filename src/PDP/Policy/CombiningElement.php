<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Decision;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\EvaluationResult;

class CombiningElement
{
    public function __construct()
    {

    }

    public function evaluate(EvaluationContext $evaluationContext): EvaluationResult
    {
        return new EvaluationResult(Decision::INDETERMINATE());
    }
}