<?php
declare(strict_types = 1);

namespace Cerberus\Core\DataType;

use Cerberus\Core\Enums\DataTypeIdentifier;

class DataTypeInteger extends DataType
{
    public function __construct()
    {
        parent::__construct(DataTypeIdentifier::INTEGER());
    }

    public function convert($source = null)
    {
        return (int) $source;
    }
}
