<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Evaluation;

use MabeEnum\Enum;

class MatchCode extends Enum
{
    const INDETERMINATE = 'INDETERMINATE';
    const MATCH = 'MATCH';
    const NO_MATCH = 'NO_MATCH';
}