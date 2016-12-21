<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Set;

class Response
{
    /** @var Set */
    protected $results;

    public function __construct($result = null)
    {
        $this->results = new Set();

        if ($result instanceof Status) {
            $this->add(new Result($result));
        }
        if ($result instanceof Result) {
            $this->add($result);
        }
    }

    public function add(Result $result)
    {
        $this->results->add($result);
    }

    public function getResults(): Set
    {
        return $this->results;
    }
}