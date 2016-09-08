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
        parent::__construct();
        $this->decision = $decision;
        $this->status = $status;
    }

    public function merge(EvaluationResult $evaluationResult)
    {
        // todo: if StatusDetail gets implemented, Status needs to be merged here instead of set
        $this
            ->setStatus($evaluationResult->getStatus())
            ->addObligations($evaluationResult->getObligations())
            ->addAdvice($evaluationResult->getAssociatedAdvice())
            ->addAttributeCategories($evaluationResult->getAttributes())
            ->addPolicyIdentifiers($evaluationResult->getPolicyIdentifiers())
            ->addPolicySetIdentifiers($evaluationResult->getPolicySetIdentifiers());
    }
}