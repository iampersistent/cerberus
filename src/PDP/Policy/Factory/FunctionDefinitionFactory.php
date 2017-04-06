<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory;

use Cerberus\Core\DataType\DataTypeBoolean;
use Cerberus\Core\DataType\DataTypeInteger;
use Cerberus\Core\DataType\DataTypeString;
use Cerberus\PDP\Policy\FunctionDefinition;
use Cerberus\PDP\Policy\FunctionDefinition\{
    FunctionDefinitionAllOf,
    FunctionDefinitionAnyOfAny,
    FunctionDefinitionBag,
    FunctionDefinitionBagIsIn,
    FunctionDefinitionBagOneAndOnly,
    FunctionDefinitionEquality
};

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

    protected function createAnyOfAny($id)
    {
        return new FunctionDefinitionAnyOfAny($id, new DataTypeString());
    }

    protected function createBooleanAllOf($id)
    {
        return new FunctionDefinitionAllOf($id, new DataTypeBoolean());
    }

    protected function createBooleanEqual($id)
    {
        return new FunctionDefinitionEquality($id, new DataTypeBoolean());
    }

    protected function createBooleanOneAndOnly($id)
    {
        return new FunctionDefinitionBagOneAndOnly($id, new DataTypeBoolean());
    }

    protected function createIntegerBag($id)
    {
        return new FunctionDefinitionBag($id, new DataTypeInteger());
    }

    protected function createIntegerEqual($id)
    {
        return new FunctionDefinitionEquality($id, new DataTypeInteger());
    }

    protected function createIntegerOneAndOnly($id)
    {
        return new FunctionDefinitionBagOneAndOnly($id, new DataTypeInteger());
    }

    protected function createStringBag($id)
    {
        return new FunctionDefinitionBag($id, new DataTypeString());
    }

    protected function createStringEqual($id)
    {
        return new FunctionDefinitionEquality($id, new DataTypeString());
    }

    protected function createStringIsIn($id)
    {
        return new FunctionDefinitionBagIsIn($id, new DataTypeString());
    }

    protected function createStringOneAndOnly($id)
    {
        return new FunctionDefinitionBagOneAndOnly($id, new DataTypeString());
    }
}
