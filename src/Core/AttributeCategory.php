<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Map;
use Ds\Set;

class AttributeCategory
{
    protected $attributes;
    protected $attributeMapById;
    protected $categoryIdentifier;

    public function __construct($categoryIdentifier)
    {
        $this->attributes = new Set();
        $this->attributeMapById = new Map();
        $this->categoryIdentifier = $categoryIdentifier;
    }

    public function getCategory(): string
    {
        return $this->categoryIdentifier;
    }

    /**
     * @return Attribute|Attribute[]|Set
     */
    public function getAttributes($attributeId = null)
    {
        if ($attributeId) {
            return $this->attributeMapById->get($attributeId);
        }

        return $this->attributes;
    }

    /**
     * @return Attribute|null
     */
    public function getAttribute(string $attributeId)
    {
        try {
            return $this->attributeMapById->get($attributeId);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function hasAttribute($attributeId): bool
    {
        return $this->attributeMapById->hasKey($attributeId);
    }
}