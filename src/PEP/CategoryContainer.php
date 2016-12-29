<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Ds\Map;

class CategoryContainer
{
    protected $attributeMap;
    protected $categoryIdentifier;

    public function __construct(string $categoryIdentifier)
    {
        $this->categoryIdentifier = $categoryIdentifier;
        $this->attributeMap = new Map();
    }

    public function getCategoryIdentifier(): string
    {
        return $this->categoryIdentifier;
    }

    /**
     * Returns all the contained attributes as a Map of key - value pairs.
     */
    public function getAttributeMap(): Map
    {
        return $this->attributeMap;
    }

    public function addAttribute(string $id, $values): self
    {
        $this->attributeMap->put($id, $values);

        return $this;
    }

    public function getAttribute(string $id)
    {
        return $this->attributeMap->get($id, null);
    }
}