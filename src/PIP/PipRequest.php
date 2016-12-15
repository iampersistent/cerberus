<?php
declare(strict_types = 1);

namespace Cerberus\PIP;

class PipRequest
{
    protected $attributeId;
    protected $category;
    protected $dataType;
    protected $issuer;

    public function __construct($category, $attributeId, $dataType, string $issuer = '')
    {
        $this->attributeId = $attributeId;
        $this->category = $category;
        $this->dataType = $dataType;
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

    public function getDataType()
    {
        return $this->dataType;
    }

    public function getIssuer()
    {
        return $this->issuer;
    }
}