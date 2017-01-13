<?php
declare(strict_types = 1);

namespace Cerberus\Core\DataType;

use Cerberus\Core\Identifier;

class DataTypeInteger extends DataType
{
    public function __construct()
    {
        parent::__construct(Identifier::DATATYPE_INTEGER());
    }

    public function convert($source = null)
    {
        return (int) $source;
    }
}
