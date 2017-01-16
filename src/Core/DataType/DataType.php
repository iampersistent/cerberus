<?php
declare(strict_types = 1);

namespace Cerberus\Core\DataType;

use Cerberus\Core\Identifier;

abstract class DataType
{
    protected function __construct(Identifier $type)
    {
        $this->type = $type;
    }

    public function equals(DataType $dataType): bool
    {
        return $this->type->is($dataType->getType());
    }

    public function getType(): Identifier
    {
        return $this->type;
    }

    abstract public function convert($source = null);

    protected function convertToString($source): string
    {
        // todo: check for more options, such as toString() method
        return (string)$source;
    }
}
