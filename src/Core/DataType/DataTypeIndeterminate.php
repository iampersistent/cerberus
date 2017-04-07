<?php
declare(strict_types = 1);

namespace Cerberus\Core\DataType;

use Cerberus\Core\Enums\DataTypeIdentifier;

class DataTypeIndeterminate extends DataType
{
    public function __construct()
    {
        parent::__construct(DataTypeIdentifier::INDETERMINATE());
    }

    public function convert($source = null)
    {
        return $source;
    }
}
