<?php
declare(strict_types = 1);

namespace Cerberus\Core\Enums;

use MabeEnum\Enum;

class FunctionIdentifier extends Enum
{
    // from XACML.java and XACML3.java

    const ALL_OF = 'function:all-of-any';
    const AND = 'function:and';
    const ANY_OF = 'function:any-of';
    const ANY_OF_ANY = 'function:any-of-any';
    const BOOLEAN_ALL_OF = 'function:boolean-all-of';
    const BOOLEAN_EQUAL = 'function:boolean-equal';
    const BOOLEAN_ONE_AND_ONLY = 'function:boolean-one-and-only';
    const INTEGER_BAG = 'function:integer-bag';
    const INTEGER_BAG_SIZE = 'function:integer-bag-size';
    const INTEGER_EQUAL = 'function:integer-equal';
    const INTEGER_GREATER_THAN = 'function:integer-greater-than';
    const INTEGER_ONE_AND_ONLY = 'function:integer-one-and-only';
    const OR = 'function:or';
    const NOT = 'function:not';
    const STRING_BAG = 'function:string-bag';
    const STRING_BAG_SIZE = 'function:string-bag-size';
    const STRING_EQUAL = 'function:string-equal';
    const STRING_GREATER_THAN = 'function:string-greater-than';
    const STRING_IS_IN = 'function:string-is-in';
    const STRING_ONE_AND_ONLY = 'function:string-one-and-only';
}
