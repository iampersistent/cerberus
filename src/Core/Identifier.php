<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use MabeEnum\Enum;

class Identifier extends Enum
{
    // from XACML.java and XACML3.java

    const ATTRIBUTE_CATEGORY_ACTION = 'attribute-category:action';
    const ATTRIBUTE_CATEGORY_RESOURCE = 'attribute-category:resource';

    const DATATYPE_BOOLEAN = 'boolean';
    const DATATYPE_DOUBLE = 'double';
    const DATATYPE_DATETIME = 'dateTime';
    const DATATYPE_INTEGER = 'integer';
    const DATATYPE_STRING = 'string';

    const MULTIPLE_CONTENT_SELECTOR = 'multiple:content-selector';
}