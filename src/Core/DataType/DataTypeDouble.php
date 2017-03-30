<?php
declare(strict_types = 1);

namespace Cerberus\Core\DataType;

use Cerberus\Core\Enums\DataTypeIdentifier;

class DataTypeDouble extends DataType
{
    public function __construct()
    {
        parent::__construct(DataTypeIdentifier::DOUBLE());
    }

    public function convert($source = null)
    {
        return (double) $source;
    }
}
