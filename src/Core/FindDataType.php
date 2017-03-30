<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Cerberus\Core\Enums\DataTypeIdentifier;
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
                return DataTypeIdentifier::BOOLEAN;
            case 'double':
                return DataTypeIdentifier::DOUBLE;
            case 'integer':
                return DataTypeIdentifier::INTEGER;
            case 'string':
                return DataTypeIdentifier::STRING;
        }
        if ($data instanceof DateTime) {
            return DataTypeIdentifier::DATETIME;
        }

        if ('object' === $type) {
            $type = get_class($data);
        }

        throw new DataTypeException("$type is not a valid data type");
    }
}
