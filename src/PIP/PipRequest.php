<?php
declare(strict_types = 1);

namespace Cerberus\PIP;

class PipRequest
{
    protected $attributeId;
    protected $category;
    protected $dataTypeId;
    protected $issuer;

    public function __construct($category, $attributeId, $dataTypeId, string $issuer = '')
    {
        $this->attributeId = $attributeId;
        $this->category = $category;
        $this->dataTypeId = $dataTypeId;
        $this->issuer = $issuer;
    }

    public function getAttributeId()
    {
        return $this->attributeId;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getDataTypeId()
    {
        return $this->dataTypeId;
    }

    public function getIssuer()
    {
        return $this->issuer;
    }
}
