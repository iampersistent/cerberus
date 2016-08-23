<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

class PepRequestAttributes
{
    public function __construct(String id, Identifier categoryIdentifier)
    {
        this.id = id;
        this.categoryIdentifier = categoryIdentifier;
        this.attributeMapById = new HashMap<Identifier, StdMutableAttribute>();
        this.wrappedRequestAttributes = new StdMutableRequestAttributes();
        this.wrappedRequestAttributes.setCategory(categoryIdentifier);
        this.wrappedRequestAttributes.setXmlId(id);
    }

    public function addAttribute(string $name, ...$values)
    {
        if (empty($values)) {
            throw new IllegalArgumentException("Null attribute value provided for attribute: " + name);
        }
         $mutableAttribute = $this->attributeMapById->get($name); // MutableAttribute
        if ($mutableAttribute == null) {
            $mutableAttribute = new MutableAttribute();
            $mutableAttribute->setAttributeId($name);
            $mutableAttribute->setCategory(categoryIdentifier);
            $mutableAttribute->setIncludeInResults(false);
            $mutableAttribute->setIssuer(issuer == null ? "" : issuer);
            $attributeMapById->put($name, $mutableAttribute);
            wrappedRequestAttributes->add($mutableAttribute);
        }
//        for ($values as $value) {
//            if ($value) {
//                $mutableAttribute->addValue(new AttributeValue(dataTypeId, $value)); // passed through if needed
//            }
//        }
    }
}