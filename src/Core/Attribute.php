<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Cerberus\PDP\Policy\Expressions\AttributeValue;
use Ds\Set;

class Attribute
{
    protected $attributeId;
    protected $category;
    protected $includeInResults;
    protected $issuer = '';
    protected $values = [];

    public function __construct(string $attributeId = null, string $category = null)
    {
        $this->attributeId = $attributeId;
        $this->category = $category;
        $this->values = new Set();
    }

    public function getAttributeId()
    {
        return $this->attributeId;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setIncludeInResults(bool $include): self
    {
        $this->includeInResults = $include;

        return $this;
    }

    public function getIssuer(): string
    {
        return $this->issuer;
    }

    public function addValue(AttributeValue $value): self
    {
        $this->values->add($value);

        return $this;
    }

    public function getValues(): Set
    {
        return $this->values;
    }
}
