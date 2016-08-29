<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Map;
use Ds\Set;

class MutableRequest
{
    /** @var Set */
    protected $requestAttributes;
    protected $requestAttributesByCategoryId;
    protected $requestAttributesById;

    public function __construct()
    {
        $this->requestAttributes = new Set();
        $this->requestAttributesByCategoryId = new Map();
        $this->requestAttributesById = new Map();
    }

    public function add(RequestAttributes $newRequestAttributes)
    {
        $this->requestAttributes->add($newRequestAttributes);
        if ($this->requestAttributesByCategoryId->hasKey($newRequestAttributes->getCategory())) {
            $listRequestAttributesForCategoryId = $this->requestAttributesByCategoryId->get($newRequestAttributes->getCategory());
        } else {
            $listRequestAttributesForCategoryId = new Set();
            $this->requestAttributesByCategoryId->put($newRequestAttributes->getCategory(),
                $listRequestAttributesForCategoryId);
        }
        $listRequestAttributesForCategoryId->add($newRequestAttributes);
        if ($newRequestAttributes->getId()) {
            $this->requestAttributesById->put($newRequestAttributes->getId(), $newRequestAttributes);
        }
        $attributeCategoryIncludeInResult = null; // MutableAttributeCategory
        foreach ($newRequestAttributes->getAttributes() as $attribute) { // Attribute
            if ($attribute->getIncludeInResults()) {
                if ($attributeCategoryIncludeInResult == null) {
                    $attributeCategoryIncludeInResult = new MutableAttributeCategory();
                    $attributeCategoryIncludeInResult->setCategory($newRequestAttributes->getCategory());
                }
                $attributeCategoryIncludeInResult->add($attribute);
            }
        }
        if ($attributeCategoryIncludeInResult != null) {
            if ($this->requestAttributesIncludeInResult == EMPTY_ATTRIBUTE_CATEGORY_LIST) {
                $this->requestAttributesIncludeInResult = new Map();
            }
            $this->requestAttributesIncludeInResult->add($attributeCategoryIncludeInResult);
        }
    }

}