<?php
declare(strict_types = 1);

namespace Cerberus\Core\DataType;

use Cerberus\Core\Identifier;

class DataTypeBoolean extends DataType
{
    public function __construct()
    {
        parent::__construct(Identifier::DATATYPE_BOOLEAN());
    }

    public function convert($source = null)
    {
        // TODO: handle more options, like strings 'TRUE' or 'true'
        return (bool) $source;
    }
}
