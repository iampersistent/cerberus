<?php
declare(strict_types = 1);

namespace Cerberus\PDP;

use Cerberus\Core\Decision;
use Cerberus\Core\Result;
use Cerberus\Core\Status;

class MutableResult extends Result
{
    public function setDecision(Decision $decision)
    {
        $this->decision = $decision;
    }

    public function setStatus(Status $status)
    {
        $this->status = $status;
    }

    public function addPolicyIdentifiers($policyIdentifiers)
    {

    }

    public function addPolicySetIdentifiers($policySetIdentifiers)
    {

    }

    public function addAttributeCategories($attributeCategories)
    {

    }
}