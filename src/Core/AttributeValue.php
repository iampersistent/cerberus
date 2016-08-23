<?php
declare(strict_types = 1);

namespace Cerberus\Core;

class AttributeValue
{
    protected $dataTypeId;
    protected $value;

    public function __construct($dataTypeId, $value)
    {
        $this->dataTypeId = $dataTypeId;
        $this->value = $value;
    }

    public function getDataTypeId()
    {
        return $this->dataTypeId;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}