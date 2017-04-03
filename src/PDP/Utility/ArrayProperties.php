<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Utility;

class ArrayProperties extends Properties
{
    public function __construct($properties)
    {
        if (isset($properties['factories'])) {
            $this->properties['factory'] = $properties['factories'];
            unset($properties['factories']);
        }

        $this->properties = array_merge($this->properties, $properties);
    }

    public function all(): array
    {
        return $this->properties;
    }

    public function merge(ArrayProperties $properties)
    {
        return new ArrayProperties(array_replace_recursive($this->properties, $properties->all()));
    }
}