<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory;

use Cerberus\PDP\Policy\Expressions\AttributeSelector;

class AttributeSelectorFactory
{
    public static function create($data): AttributeSelector
    {
        return new AttributeSelector(
            $data['category'],
            $data['dataType'],
            $data['mustBePresent'],
            $data['path']
        );
    }
}