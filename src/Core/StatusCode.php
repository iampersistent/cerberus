<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use MabeEnum\Enum;

class StatusCode extends Enum
{
    const STATUS_CODE_OK = 'OK';
    const STATUS_CODE_MISSING_ATTRIBUTE = 'Missing attribute';
    const STATUS_CODE_SYNTAX_ERROR = 'Syntax error';
    const STATUS_CODE_PROCESSING_ERROR = 'Processing error';
}