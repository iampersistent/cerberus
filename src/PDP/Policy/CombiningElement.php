<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Decision;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\EvaluationResult;

class CombiningElement
{
    protected $evaluatable;
    protected $targetedCombiningParameters;

    public function __construct($evaluatable, $targetedCombiningParameters)
    {
        $this->evaluatable = $evaluatable;
        $this->targetedCombiningParameters = $targetedCombiningParameters;
    }

    public function evaluate(EvaluationContext $evaluationContext): EvaluationResult
    {
        return $this->evaluatable->evaluate($evaluationContext);
    }

    public function getTargetedCombiningParameters()
    {
        return $this->targetedCombiningParameters;
    }
}
