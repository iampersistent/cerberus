<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\{
    AttributeValue, Exception\IllegalArgumentException, FindDataType, Attribute, RequestAttributes
};
use Ds\Map;

class PepRequestAttributes extends RequestAttributes
{
    public function __construct($id, $categoryIdentifier)
    {
        $this->id = $id;
        parent::__construct($categoryIdentifier);
    }

    public function addAttribute(string $name, ...$values)
    {
        if (empty($values)) {
            throw new IllegalArgumentException("Null attribute value provided for attribute: " + name);
        }
        if (null === $attribute = $this->attributes->get($name, null)) {
            $attribute = new Attribute($name, $this->categoryIdentifier);
            $attribute->setIncludeInResults(false);
            //$attribute->setIssuer(issuer == null ? "" : issuer);
            $this->attributes->put($name, $attribute);
        }
        foreach ($values as $value) {
            $dataTypeId = FindDataType::handle($value);
            $attribute->addValue(new AttributeValue($dataTypeId, $value)); // passed through if needed
        }
    }
}