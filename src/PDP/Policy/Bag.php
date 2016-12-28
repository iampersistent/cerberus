<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Ds\Set;
use Iterator;

class Bag
{
    protected $values;

    public function __construct()
    {
        $this->values = new Set();
    }

    public function add($value): self
    {
        $this->values->add($value);

        return $this;
    }

    public function merge($values): self
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

    public function isEmpty(): bool
    {
        return $this->values->isEmpty();
    }

    public function size(): int
    {
        return $this->values->count();
    }
}