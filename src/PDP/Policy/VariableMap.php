<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Ds\Map;
use Ds\Set;

class VariableMap
{
    protected $mapVariableDefinitions;
    protected $variableDefinitions;

    public function __construct()
    {
        $this->mapVariableDefinitions = new Map();
        $this->variableDefinitions = new Set();
    }

    public function add(VariableDefinition $variableDefinition): self
    {
        $this->variableDefinitions->add($variableDefinition);
        $this->mapVariableDefinitions->put($variableDefinition->getId(), $variableDefinition);

        return $this;
    }

    /**
     * @param string $variableId
     *
     * @return VariableDefinition|null
     */
    public function getVariableDefinition(string $variableId)
    {
        return $this->mapVariableDefinitions->get($variableId, null);
    }

    public function getVariableDefinitions(): Set
    {
        return $this->variableDefinitions;
    }
}