<?php
declare(strict_types = 1);

namespace Cerberus\Core\DataType;

use DateTime;
use Cerberus\Core\Enums\DataTypeIdentifier;

class DataTypeDateTime extends DataType
{
    public function __construct()
    {
        parent::__construct(DataTypeIdentifier::DATETIME());
    }

    public function convert($source = null)
    {
        return new DateTime($source ?: 'now');
    }
}
