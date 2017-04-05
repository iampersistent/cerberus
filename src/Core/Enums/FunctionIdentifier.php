<?php
declare(strict_types = 1);

namespace Cerberus\Core\Enums;

use MabeEnum\Enum;

class FunctionIdentifier extends Enum
{
    // from XACML.java and XACML3.java

    const ANY_OF_ANY = 'function:any-of-any';
    const BOOLEAN_ALL_OF = 'function:boolean-all-of';
    const BOOLEAN_EQUAL = 'function:boolean-equal';
    const INTEGER_BAG = 'function:integer-bag';
    const INTEGER_EQUAL = 'function:integer-equal';
    const STRING_BAG = 'function:string-bag';
    const STRING_EQUAL = 'function:string-equal';
    const STRING_IS_IN = 'function:string-is-in';
    const STRING_ONE_AND_ONLY = 'function:string-one-and-only';
}