<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory;

use Cerberus\Core\DataType\DataTypeBoolean;
use Cerberus\Core\DataType\DataTypeString;
use Cerberus\Core\Identifier;
use Cerberus\PDP\Policy\FunctionDefinition;
use Cerberus\PDP\Policy\FunctionDefinition\FunctionDefinitionAnyOfAny;
use Cerberus\PDP\Policy\FunctionDefinition\FunctionDefinitionBag;
use Cerberus\PDP\Policy\FunctionDefinition\FunctionDefinitionBagIsIn;
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

    protected function createAnyOfAny($id)
    {
        return new FunctionDefinitionAnyOfAny($id, new DataTypeString());
    }

    protected function createBooleanEqual($id)
    {
        return new FunctionDefinitionEquality($id, new DataTypeBoolean());
    }

    protected function createIntegerEqual($id)
    {
        return new FunctionDefinitionEquality($id, new DataTypeBoolean());
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
