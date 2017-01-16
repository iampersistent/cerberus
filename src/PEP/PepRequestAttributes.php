<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\{
    Exception\IllegalArgumentException, FindDataType, Attribute, RequestAttributes
};
use Cerberus\PDP\Policy\Expressions\AttributeValue;

class PepRequestAttributes extends RequestAttributes
{
    public function __construct($id, $categoryIdentifier)
    {
        parent::__construct($id, $categoryIdentifier);
    }

    public function addAttribute(string $name, ...$values): self
    {
        if (empty($values)) {
            throw new IllegalArgumentException("Null attribute value provided for attribute: $name");
        }
        if (! $attribute = $this->attributeMapById->get($name, null)) {
            $attribute = new Attribute($name, $this->categoryIdentifier);
            $attribute->setIncludeInResults(false);
            //$attribute->setIssuer($issuer ?? '');
            $this->attributeMapById->put($name, $attribute);
            $this->attributes->add($attribute);
        }
        foreach ($values as $value) {
            $dataTypeId = FindDataType::handle($value);
            $attribute->addValue(new AttributeValue($dataTypeId, $value)); // passed through if needed
        }

        return $this;
    }
}
