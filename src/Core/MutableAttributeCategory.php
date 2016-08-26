<?php
declare(strict_types = 1);

namespace Cerberus\Core;

class MutableAttributeCategory extends RequestAttributes
{
    public function add(MutableAttribute $attribute)
    {
        $this->attributes->add($attribute);
    }
}