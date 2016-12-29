<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory;

use Cerberus\PDP\Policy\Expressions\AttributeDesignator;

class AttributeDesignatorFactory
{
    public static function create($data): AttributeDesignator
    {
        return new AttributeDesignator(
            $data['category'],
            $data['dataType'],
            $data['mustBePresent'],
            $data['attributeId']
        );
    }
}