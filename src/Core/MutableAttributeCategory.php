<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Map;

class MutableAttributeCategory
{
    protected $attributes = [];
    protected $categoryIdentifier;

    public function __construct($categoryIdentifier)
    {
        $this->categoryIdentifier = $categoryIdentifier;
        $this->attributes = new Map();
    }
public function add(MutableAttribute $attribute) {
$this->attributes->add($attribute);
}
    /**
     * Gets the {@link Identifier} for the XACML Category of this <code>AttributeCategory</code>.
     */
    public function getCategory(): string
    {
        return $this->categoryIdentifier;
    }

    /**
     * Gets the <code>Collection</code> of {@link Attribute}s in this <code>AttributeCategory</code>. If there
     * are no <code>Attribute</code>s in this <code>AttributeCategory</code> then an empty
     * <code>Collection</code> must be returned. The returned <code>Collection</code> should not be modified.
     * Implementations are free to return an immutable view to enforce $this->
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Gets an <code>Iterator</code> over all of the {@link Attribute}s in this <code>AttributeCategory</code>
     * with the given {@link Identifier} matching their XACML AttributeId.
     */
    public function getAttribute(string $attributeId)
    {
        return $this->attributes[$attributeId] ?? null;
    }

    /**
     * Determines if there is at least one {@link Attribute} in this <code>AttributeCategory</code> whose
     * XACML AttributeId matches the given {@link Identifier}.
     */
    public function hasAttribute(string $attributeId): bool
    {
        return (bool) $this->attributes[$attributeId] ?? false;
    }
}