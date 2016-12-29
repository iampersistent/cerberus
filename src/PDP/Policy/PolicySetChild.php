<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\StatusCode;
use Cerberus\PDP\Policy\Traits\PolicyComponent;

abstract class PolicySetChild
{
    protected $policyDefaults;

    public function __construct()
    {
        $this->policyDefaults = new PolicyDefaults();
    }

    public function getPolicyDefaults()
    {
        return $this->policyDefaults;
    }
}