<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Evaluation;

use Cerberus\Core\Request;
use Cerberus\PDP\Policy\PolicyFinder;
use Cerberus\PIP\PipFinder;

class EvaluationContextFactory
{
    /** @var PolicyFinder */
    protected $policyFinder;
    /** @var PipFinder */
    protected $pipFinder;
    protected $properties = null; // Properties

    public function __construct(PolicyFinder $policyFinder, PipFinder $pipFinder)
    {
        $this->pipFinder = $pipFinder;
        $this->policyFinder = $policyFinder;
    }

    public function getEvaluationContext(Request $request): EvaluationContext
    {
        return new EvaluationContext($request, $this->policyFinder, $this->pipFinder);
    }
}