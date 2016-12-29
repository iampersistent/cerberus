<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Status;

class PolicyFinderResult
{
    /** @var PolicyDef */
    protected $policyDef;

    /** @var Status */
    protected $status;

    public function __construct(Status $status, PolicyDef $policyDef = null)
    {
        $this->policyDef = $policyDef;
        $this->status = $status;
    }

    public function getPolicyDef(): PolicyDef
    {
        return $this->policyDef;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}