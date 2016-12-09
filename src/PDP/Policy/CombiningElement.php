<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Decision;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\EvaluationResult;

class CombiningElement
{
    protected $evaluatable;

    public function __construct($evaluatable, $combiningElement)
    {
        $this->evaluatable = $evaluatable;
    }

    public function evaluate(EvaluationContext $evaluationContext): EvaluationResult
    {
        return $this->evaluatable->evaluate($evaluationContext);
    }
}