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

    public function convert($source = null)
    {
        if (! $source || is_string($source)) {
            return (string) $source;
        } else {
            return $this->convertToString($source);
        }
    }
}
