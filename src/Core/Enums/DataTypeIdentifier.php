<?php
declare(strict_types = 1);

namespace Cerberus\Core\Enums;

use MabeEnum\Enum;

class DataTypeIdentifier extends Enum
{
    // from XACML.java and XACML3.java

    const BOOLEAN = 'boolean';
    const DOUBLE = 'double';
    const INTEGER = 'integer';
    const STRING = 'string';
    const XPATH_EXPRESSION = 'path';
    const INDETERMINATE = 'indeterminate';
    const TIME = 'time';
    const DATE = 'date';
    const DATETIME = 'dateTime';
    const DATETIME_DURATION = 'dateTimeDuration';
    const YEARMONTH_DURATION = 'yearMonthDuration';
}
