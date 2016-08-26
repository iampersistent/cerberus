<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\{
    AttributeValue, Exception\IllegalArgumentException, FindDataType, MutableAttribute, MutableRequestAttributes, RequestAttributes
};
use Ds\Map;

class PepRequestAttributes extends RequestAttributes
{
    protected $wrappedRequestAttributes;

    public function __construct($id, $categoryIdentifier)
    {
        $this->id = $id;
        $this->wrappedRequestAttributes = new MutableRequestAttributes($id, $categoryIdentifier);
        parent::__construct($categoryIdentifier);
    }

    public function addAttribute(string $name, ...$values)
    {
        if (empty($values)) {
            throw new IllegalArgumentException("Null attribute value provided for attribute: " + name);
        }
        $mutableAttribute = $this->attributes->get($name); // MutableAttribute
        if ($mutableAttribute == null) {
            $mutableAttribute = new MutableAttribute($name, $this->categoryIdentifier);
            $mutableAttribute->setIncludeInResults(false);
            //$mutableAttribute->setIssuer(issuer == null ? "" : issuer);
            $this->attributes->put($name, $mutableAttribute);
            $this->wrappedRequestAttributes->add($mutableAttribute);
        }
        foreach ($values as $value) {
            $dataTypeId = FindDataType::handle($value);
            $mutableAttribute->addValue(new AttributeValue($dataTypeId, $value)); // passed through if needed
        }
    }

    public function getWrappedRequestAttributes(): MutableRequestAttributes
    {
        return $this->wrappedRequestAttributes;
    }
}