<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Cerberus\PEP\PepRequestAttributes;
use Ds\Map;

class MutableRequest
{
    public function __construct()
    {
        $a =0;
    }

    public function add(PepRequestAttributes $newRequestAttributes) 
    {
        $this->requestAttributes->add($newRequestAttributes);
        $listRequestAttributesForCategoryId = $this->requestAttributesByCategoryId->get($newRequestAttributes->getCategory());
        if ($listRequestAttributesForCategoryId == null) {
//            $listRequestAttributesForCategoryId = new ArrayList<RequestAttributes>();
            $this->requestAttributesByCategoryId->put($newRequestAttributes->getCategory(),
                $listRequestAttributesForCategoryId);
        }
        $listRequestAttributesForCategoryId->add($newRequestAttributes);
        if ($newRequestAttributes->getXmlId() != null) {
            $this->requestAttributesByXmlId->put($newRequestAttributes->getXmlId(), $newRequestAttributes);
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