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
            // policy files must return a php array
            $this->rootPolicies = $this->handlePolicies(require $policy);
        }
        $this->needsInit = false;
    }
}