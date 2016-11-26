<?php
declare(strict_types = 1);

namespace Cerberus\PDP;

class CerberusEngineFactory
{
    public function newEngine($properties)
    {
        return new CerberusEngine($policyFinder, $pipFinder);
    }
}