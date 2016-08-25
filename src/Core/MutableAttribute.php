<?php
declare(strict_types = 1);

namespace Cerberus\Core;

class MutableAttribute
{
    protected $attributeId;
    protected $categoryIdentifier;
    protected $includeInResults;
    protected $values = [];

    public function __construct(string $attributeId = null, string $categoryIdentifier = null)
    {
        $this->attributeId = $attributeId;
        $this->categoryIdentifier = $categoryIdentifier;

    }

    public function addValue(AttributeValue $value)
    {
        $this->values[] = $value;
    }

    public function setIncludeInResults(bool $include): self
    {
        $this->includeInResults = $include;

        return $this;
    }
}