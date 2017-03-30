<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Combiner;

use Cerberus\PDP\Evaluation\EvaluationContext;
use Cerberus\PDP\Evaluation\EvaluationResult;
use Cerberus\PDP\Policy\CombiningElement;
use Ds\Set;

abstract class CombiningAlgorithm
{
    const DENY_OVERRIDES = 'rule-combining-algorithm:deny-overrides';
    const DENY_UNLESS_PERMIT = 'rule-combining-algorithm:deny-unless-permit';

    protected $identifier;

    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @param EvaluationContext      $evaluationContext
     * @param CombiningElement[]|Set $elements
     * @param                        $combinerParameters
     *
     * @return EvaluationResult
     */
    abstract public function combine(
        EvaluationContext $evaluationContext,
        $elements,
        $combinerParameters
    ): EvaluationResult;
}
