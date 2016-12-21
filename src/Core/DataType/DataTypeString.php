<?php
declare(strict_types = 1);

namespace Cerberus\Core\DataType;

use Cerberus\Core\Identifier;

class DataTypeString extends DataType
{
    public function __construct()
    {
        parent::__construct(Identifier::DATATYPE_STRING());
    }
}