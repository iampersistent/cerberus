<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Evaluation;

use Cerberus\Core\Request;
use Cerberus\PDP\Policy\PolicyFinder;
use Cerberus\PIP\PipFinder;

class EvaluationContextFactory
{
    protected $functionDefinitionFactory;
    /** @var PolicyFinder */
    protected $policyFinder;
    /** @var PipFinder */
    protected $pipFinder;
    protected $properties = null; // Properties

    public function __construct(PolicyFinder $policyFinder, PipFinder $pipFinder, $functionDefinitionFactory)
    {
        $this->functionDefinitionFactory = $functionDefinitionFactory;
        $this->pipFinder = $pipFinder;
        $this->policyFinder = $policyFinder;
    }

    public function getEvaluationContext(Request $request): EvaluationContext
    {
        return new EvaluationContext($request, $this->policyFinder, $this->pipFinder, $this->functionDefinitionFactory);
    }
}