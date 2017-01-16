<?php
declare(strict_types = 1);

namespace Cerberus\Core\Factory;

use Cerberus\Core\DataType\DataType;
use Cerberus\Core\DataType\DataTypeBoolean;
use Cerberus\Core\DataType\DataTypeString;
use Cerberus\Core\Exception\DataTypeException;
use Cerberus\Core\Identifier;
use DateTime;

class DataTypeFactory
{
    public static function create($data): DataType
    {
        $type = gettype($data);
        switch ($type) {
            case 'array':
                throw new DataTypeException('There is no defined array data type');
            case 'boolean':
                return new DataTypeBoolean();
            case 'double':
                return Identifier::DATATYPE_DOUBLE;
            case 'integer':
                return Identifier::DATATYPE_INTEGER;
            case 'string':
                return new DataTypeString();
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
