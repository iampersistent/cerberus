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

    /**
     * Gets the {@link Identifier} for the XACML Category of this <code>AttributeCategory</code>.
     */
    public function getCategory(): string
    {
        return $this->categoryIdentifier;
    }

    /**
     * @return Attribute[]|Set
     */
    public function getAttributes($attributeId = null)
    {
        if ($attributeId) {
            return $this->attributeMapById->get($attributeId);
        }

        return $this->attributes;
    }

    /**
     * Gets an <code>Iterator</code> over all of the {@link Attribute}s in this <code>AttributeCategory</code>
     * with the given {@link Identifier} matching their XACML AttributeId.
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