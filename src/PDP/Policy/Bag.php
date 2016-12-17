<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Ds\Set;

class Bag
{
    protected $values;

    public function __construct()
    {
        $this->values = new Set();
    }

    public function add(...$values)
    {
        foreach ($values as $value) {
            $this->values->add($value);
        }

        return $this;
    }

    public function getAttributeValues()
    {
        return $this->values;
    }

    public function size()
    {
        return $this->values->count();
    }
}