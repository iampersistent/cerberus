<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Cerberus\Core\Exception\DataTypeException;
use DateTime;

class FindDataType
{
    public static function handle($data)
    {
        $type = gettype($data);
        switch ($type) {
            case 'array':
                throw new DataTypeException('There is no defined array data type');
            case 'boolean':
                return Identifier::DATATYPE_BOOLEAN;
            case 'double':
                return Identifier::DATATYPE_DOUBLE;
            case 'integer':
                return Identifier::DATATYPE_INTEGER;
            case 'string':
                return Identifier::DATATYPE_STRING;
        }
        if ($data instanceof DateTime) {
            return Identifier::DATATYPE_DATETIME;
        }

        if ('object' === $type) {
            $type = get_class($data);
        }

        throw new DataTypeException("$type is not a valid data type");
    }
}
