<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory;

use Cerberus\PDP\Policy\FunctionDefinition\FunctionDefinitionEquality;

class FunctionDefinitionFactory
{
    protected $map;

    public function __construct($map = [])
    {

    }

    public function getFunctionDefinition($id)
    {
        $parts = explode(':', $id);
        $method = 'create' . str_replace('-', '', ucwords($parts[1], '-'));

        return $this->$method($id);
    }

    protected function createStringEqual($id)
    {
        return new FunctionDefinitionEquality($id, 'string');
    }
}