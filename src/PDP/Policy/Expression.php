<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Policy\Traits\PolicyComponent;

abstract class Expression implements PolicyElement
{
    use PolicyComponent;

    public abstract function evaluate(EvaluationContext $evaluationContext, PolicyDefaults $policyDefaults): ExpressionResult;
}
