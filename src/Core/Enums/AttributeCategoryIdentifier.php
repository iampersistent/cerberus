<?php
declare(strict_types = 1);

namespace Cerberus\Core\Enums;

use MabeEnum\Enum;

class AttributeCategoryIdentifier extends Enum
{
    // from XACML.java and XACML3.java

    const ACTION = 'attribute-category:action';
    const RESOURCE = 'attribute-category:resource';
}
