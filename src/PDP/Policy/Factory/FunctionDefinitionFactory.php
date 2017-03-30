<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory;

use Cerberus\Core\DataType\DataTypeBoolean;
use Cerberus\Core\DataType\DataTypeInteger;
use Cerberus\Core\DataType\DataTypeString;
use Cerberus\PDP\Policy\FunctionDefinition;
use Cerberus\PDP\Policy\FunctionDefinition\FunctionDefinitionAllOf;
use Cerberus\PDP\Policy\FunctionDefinition\FunctionDefinitionAnyOfAny;
use Cerberus\PDP\Policy\FunctionDefinition\FunctionDefinitionBag;
use Cerberus\PDP\Policy\FunctionDefinition\FunctionDefinitionBagIsIn;
use Cerberus\PDP\Policy\FunctionDefinition\FunctionDefinitionBagOneAndOnly;
use Cerberus\PDP\Policy\FunctionDefinition\FunctionDefinitionEquality;

class FunctionDefinitionFactory
{
    const ANY_OF_ANY = 'function:any-of-any';
    const BOOLEAN_ALL_OF = 'function:boolean-all-of';
    const BOOLEAN_EQUAL = 'function:boolean-equal';
    const INTEGER_BAG = 'function:integer-bag';
    const INTEGER_EQUAL = 'function:integer-equal';
    const STRING_BAG = 'function:string-bag';
    const STRING_EQUAL = 'function:string-equal';
    const STRING_IS_IN = 'function:string-is-in';
    const STRING_ONE_AND_ONLY = 'function:string-one-and-only';

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

    protected function createIntegerBag($id)
    {
        return new FunctionDefinitionBag($id, new DataTypeInteger());
    }

    protected function createIntegerEqual($id)
    {
        return new FunctionDefinitionEquality($id, new DataTypeInteger());
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
