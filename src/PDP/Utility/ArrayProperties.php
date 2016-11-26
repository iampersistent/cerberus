<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Utility;

class ArrayProperties extends Properties
{
    public function __construct($properties)
    {
        if (isset($properties['factories'])) {
            $this->properties['factory'] = $properties['factories'];
        }
    }
}