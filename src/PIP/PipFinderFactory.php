<?php
declare(strict_types = 1);

namespace Cerberus\PIP;

use Cerberus\PDP\Utility\Properties;

class PipFinderFactory
{
    public function getPipFinder(Properties $properties): PipFinder
    {
        return new PipFinder();
    }
}