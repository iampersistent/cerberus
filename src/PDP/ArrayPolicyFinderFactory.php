<?php
declare(strict_types = 1);

namespace Cerberus\PDP;

use Cerberus\PDP\Utility\Properties;

class ArrayPolicyFinderFactory extends PolicyFinderFactory
{
    protected function init(Properties $properties)
    {
        $policies = $properties->get('rootPolicies', []);
        foreach ($policies as $policy) {
            include $policy;
            $parts = pathinfo($policy);
            $policyProperty = $parts['filename'];

            $this->rootPolicies = $this->handlePolicies($$policyProperty);
        }
        $this->needsInit = false;
    }
}