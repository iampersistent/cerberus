<?php
declare(strict_types = 1);

namespace Cerberus\Core\Enums;

use MabeEnum\Enum;

class AttributeIdentifier extends Enum
{
    // from XACML.java and XACML3.java

    const ACTION_CATEGORY = 'attribute-category:action';
    const RESOURCE_CATEGORY = 'attribute-category:resource';

    const VALUE = 'attributeValue';
    const SELECTOR = 'attributeSelector';
    const DESIGNATOR = 'attributeDesignator';
}
