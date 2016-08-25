<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use DateTime;

class FindDataType
{
    public static function handle($data)
    {
        $type = gettype($data);
        switch ($type) {
            case 'array':
                return CategoryType::ID_DATATYPE_ARRAY;
            case 'boolean':
                return CategoryType::ID_DATATYPE_BOOLEAN;
            case 'double':
                return CategoryType::ID_DATATYPE_DOUBLE;
            case 'integer':
                return CategoryType::ID_DATATYPE_INTEGER;
            case 'string':
                return CategoryType::ID_DATATYPE_STRING;
        }
        if ($data instanceof DateTime) {
            return CategoryType::ID_DATATYPE_DATETIME;
        }
    }
}