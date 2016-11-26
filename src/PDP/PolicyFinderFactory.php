<?php
declare(strict_types = 1);

namespace Cerberus\PDP;

use Cerberus\PDP\Policy\PolicyDef;
use Cerberus\PDP\Policy\PolicyFinder;
use Cerberus\PDP\Policy\PolicySet;
use Ds\Map;
use Ds\Set;

class PolicyFinderFactory
{
    protected $needsInit = true;
    /** @var PolicyDef[]|Set */
    protected $referencedPolicies;

    /** @var PolicyDef[]|Set */
    protected $rootPolicies;

    public function __construct()
    {
        $this->referencedPolicies = new Set();
        $this->rootPolicies = new Set();
    }

    public function getPolicyFinder($properties): PolicyFinder
    {
        $this->init($properties);

        return new PolicyFinder($this->rootPolicies, $this->referencedPolicies);
    }
}