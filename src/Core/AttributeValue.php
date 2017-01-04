<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Cerberus\Core\Exception\IllegalArgumentException;
use Cerberus\PDP\Contract\PolicyElement;

class AttributeValue implements PolicyElement
{
    protected $dataTypeId;
    protected $value;

    public function __construct($dataTypeId, $value = null)
    {
        if (func_num_args() === 1) {
            throw new IllegalArgumentException('If you need a null attribute value, it must be explicitly set');
        }

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
