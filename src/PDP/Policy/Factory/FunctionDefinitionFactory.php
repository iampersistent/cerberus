<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory;

use Cerberus\Core\Identifier;
use Cerberus\PDP\Policy\FunctionDefinition;
use Cerberus\PDP\Policy\FunctionDefinition\FunctionDefinitionBagOneAndOnly;
use Cerberus\PDP\Policy\FunctionDefinition\FunctionDefinitionEquality;

class FunctionDefinitionFactory
{
    protected $map;

    public function __construct($map = [])
    {
    }

    /**
     * @param $id
     *
     * @return FunctionDefinition|null
     */
    public function getFunctionDefinition($id)
    {
        $parts = explode(':', $id);
        $method = 'create' . str_replace('-', '', ucwords($parts[1], '-'));

        return $this->$method($id);
    }

    protected function createStringOneAndOnly($id)
    {
        return new FunctionDefinitionBagOneAndOnly($id, Identifier::DATATYPE_STRING);
    }

    protected function createStringEqual($id)
    {
        return new FunctionDefinitionEquality($id, Identifier::DATATYPE_STRING);
    }
}