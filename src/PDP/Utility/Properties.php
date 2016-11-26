<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Utility;

abstract class Properties
{
    protected $properties = [];

    public function get($property, $default = null)
    {
        $propertyPath = explode('.', $property);

        $value = $this->properties;
        foreach ($propertyPath as $element) {
            if (!isset($value[$element])) {
                return $default;
            }
            $value = $value[$element];
        }

        return $value;
    }
}