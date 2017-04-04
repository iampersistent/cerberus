<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Enums\{
    AttributeIdentifier, ResourceIdentifier
};

class ResourceObject extends CategoryContainer
{
    protected $id;
    protected $type;

    public function __construct(string $type, string $id)
    {
        $this->id = $id;
        $this->type = $type;
        parent::__construct(AttributeIdentifier::RESOURCE_CATEGORY);
        $this->addAttribute(ResourceIdentifier::RESOURCE_ID, $id);
        $this->addAttribute(ResourceIdentifier::RESOURCE_TYPE, $type);
    }
}