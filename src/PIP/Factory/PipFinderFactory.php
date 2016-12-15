<?php
declare(strict_types = 1);

namespace Cerberus\PIP\Factory;

use Cerberus\PDP\Utility\Properties;
use Cerberus\PIP\Engine\RequestEngine;
use Cerberus\PIP\PipFinder;

class PipFinderFactory
{
    public function getPipFinder(Properties $properties): PipFinder
    {
        $engines = [];

        return new PipFinder($engines);
    }
}