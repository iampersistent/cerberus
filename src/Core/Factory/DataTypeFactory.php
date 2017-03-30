<?php
declare(strict_types = 1);

namespace Cerberus\Core\Factory;

use DateTime;
use Cerberus\Core\DataType\{
    DataType, DataTypeBoolean, DataTypeDateTime, DataTypeDouble, DataTypeInteger, DataTypeString
};
use Cerberus\Core\Exception\DataTypeException;

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
                return new DataTypeDouble();
            case 'integer':
                return new DataTypeInteger();
            case 'string':
                return new DataTypeString();
        }
        if ($data instanceof DateTime) {
            return new DataTypeDateTime();
        }

        if ('object' === $type) {
            $type = get_class($data);
        }

        throw new DataTypeException("$type is not a valid data type");
    }
}
