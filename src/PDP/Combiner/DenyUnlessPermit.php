<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Combiner;

use Cerberus\Core\Decision;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\EvaluationResult;
use Cerberus\PDP\Exception\EvaluationException;

class DenyUnlessPermit extends CombiningAlgorithm
{
    public function combine(EvaluationContext $evaluationContext, $elements, $combinerParameters): EvaluationResult
    {
        $combinedResult = new EvaluationResult(Decision::DENY());
            
        foreach ($elements as $combiningElement) {    
            $evaluationResult = $combiningElement->evaluate($evaluationContext);

            switch ($evaluationResult->getDecision()->getValue()) {
                case Decision::DENY:
                    $combinedResult->merge($evaluationResult);
                    break;
                case Decision::INDETERMINATE:
                case Decision::INDETERMINATE_DENY_PERMIT:
                case Decision::INDETERMINATE_DENY:
                case Decision::INDETERMINATE_PERMIT:
                case Decision::NOT_APPLICABLE:
                    break;
                case Decision::PERMIT:
                    return $evaluationResult;
                default:
                    throw new EvaluationException('Illegal Decision: ' . $evaluationResult->getDecision()->getValue());
            }
        }

        return $combinedResult;
    }

}
