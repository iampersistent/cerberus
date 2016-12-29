<?php
declare(strict_types = 1);

namespace Cerberus\Core\DataType;

use Cerberus\Core\Identifier;

abstract class DataType
{
    protected function __construct(Identifier $type)
    {
        $this->type = $type;
    }
}