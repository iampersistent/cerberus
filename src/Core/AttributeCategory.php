<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Map;

class AttributeCategory
{
    protected $attributes;
    protected $categoryIdentifier;

    public function __construct($categoryIdentifier)
    {
        $this->attributes = new Map();
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
     * @return Attribute[]|Map
     */
    public function getAttributes(): Map
    {
        return $this->attributes;
    }

    /**
     * Gets an <code>Iterator</code> over all of the {@link Attribute}s in this <code>AttributeCategory</code>
     * with the given {@link Identifier} matching their XACML AttributeId.
     */
    public function getAttribute(string $attributeId)
    {
        try {
            return $this->attributes->get($attributeId);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function hasAttribute($attributeId): bool
    {
        return $this->attributes->hasKey($attributeId);
    }
}