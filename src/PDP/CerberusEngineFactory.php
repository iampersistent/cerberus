<?php
declare(strict_types = 1);

namespace Cerberus\PDP;

use Cerberus\PDP\Utility\Properties;

class CerberusEngineFactory
{
    public function newEngine(Properties $properties)
    {
        $policyFinderFactory = $properties->get('factory.policyFinder');
        $policyFinder = (new $policyFinderFactory())
            ->getPolicyFinder($properties);

        $pipFinderFactory = $properties->get('factory.pipFinder');
        $pipFinder = (new $pipFinderFactory())
            ->getPipFinder($properties);

        return new CerberusEngine($policyFinder, $pipFinder);
    }
}