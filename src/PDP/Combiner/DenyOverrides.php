<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Combiner;

use Cerberus\Core\Decision;
use Cerberus\PDP\Evaluation\{
    EvaluationContext, EvaluationResult
};
use Cerberus\PDP\Exception\EvaluationException;

class DenyOverrides extends CombiningAlgorithm
{
    public function combine(EvaluationContext $evaluationContext, $elements, $combinerParameters): EvaluationResult
    {
        $atLeastOnePermit = false;

        $combinedResult = new EvaluationResult(Decision::PERMIT());

        /** @var EvaluationResult $firstIndeterminateD */
        $firstIndeterminateD = null;
        /** @var EvaluationResult $firstIndeterminateP */
        $firstIndeterminateP = null;
        /** @var EvaluationResult $firstIndeterminateDP */
        $firstIndeterminateDP = null;

        foreach ($elements as $combiningElement) {
            $evaluationResultElement = $combiningElement->evaluate($evaluationContext);

            switch ((string) $evaluationResultElement->getDecision()) {
                case Decision::DENY:
                    return $evaluationResultElement;
                case Decision::INDETERMINATE:
                case Decision::INDETERMINATE_DENY_PERMIT:
                    if (! $firstIndeterminateDP) {
                        $firstIndeterminateDP = $evaluationResultElement;
                    } else {
                        $firstIndeterminateDP->merge($evaluationResultElement);
                    }
                    break;
                case Decision::INDETERMINATE_DENY:
                    if (!$firstIndeterminateD) {
                        $firstIndeterminateD = $evaluationResultElement;
                    } else {
                        $firstIndeterminateD->merge($evaluationResultElement);
                    }
                    break;
                case Decision::INDETERMINATE_PERMIT:
                    if (!$firstIndeterminateP) {
                        $firstIndeterminateP = $evaluationResultElement;
                    } else {
                        $firstIndeterminateP->merge($evaluationResultElement);
                    }
                    break;
                case Decision::NOT_APPLICABLE:
                    break;
                case Decision::PERMIT:
                    $atLeastOnePermit = true;
                    $combinedResult->merge($evaluationResultElement);
                    break;
                default:
                    throw new EvaluationException("Illegal Decision: " . (string)$evaluationResultElement->getDecision());
            }
        }

        if ($firstIndeterminateDP) {
            return $firstIndeterminateDP;
        } else {
            if ($firstIndeterminateD && ($firstIndeterminateP || $atLeastOnePermit)) {
                return new EvaluationResult(Decision::INDETERMINATE_DENY_PERMIT(), $firstIndeterminateD->getStatus());
            } else {
                if ($firstIndeterminateD) {
                    return $firstIndeterminateD;
                } else {
                    if ($atLeastOnePermit) {
                        return $combinedResult;
                    } else {
                        if ($firstIndeterminateP) {
                            return $firstIndeterminateP;
                        } else {
                            return new EvaluationResult(Decision::NOTAPPLICABLE());
                        }
                    }
                }
            }
        }
    }
}