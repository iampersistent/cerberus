<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Combiner;

use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\EvaluationResult;

abstract class CombiningAlgorithm
{
    protected $identifier;

    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    abstract public function combine(EvaluationContext $evaluationContext, $elements, $combinerParameters): EvaluationResult;
}