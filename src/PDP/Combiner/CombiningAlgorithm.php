<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Combiner;

class CombiningAlgorithm
{
    protected $identifier;

    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }
}