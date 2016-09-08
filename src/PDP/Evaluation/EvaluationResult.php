<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Evaluation;

use Cerberus\Core\Decision;
use Cerberus\Core\Status;
use Cerberus\PDP\MutableResult;

class EvaluationResult extends MutableResult
{
    /** @var Decision */
    protected $decision;

    /** @var Status */
    protected $status;

    public function __construct(Decision $decision, Status $status = null)
    {
        $this->decision = $decision;
        $this->status = $status;
    }

    public function merge(EvaluationResult $evaluationResult)
    {
        // todo: if StatusDetail gets implemented, it needs to be merged here
        $this->setStatus($evaluationResult->getStatus());
        $this->addObligations($evaluationResult->getObligations());
        $this->addAdvice($evaluationResult->getAssociatedAdvice());
        $this->addAttributeCategories($evaluationResult->getAttributes());
        $this->addPolicyIdentifiers($evaluationResult->getPolicyIdentifiers());
        $this->addPolicySetIdentifiers($evaluationResult->getPolicySetIdentifiers());
    }
}