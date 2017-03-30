<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Enums\AttributeCategoryIdentifier;

class ResourceObject extends CategoryContainer
{
    protected $id;
    protected $type;

    public function __construct(string $type, string $id)
    {
        $this->id = $id;
        $this->type = $type;
        parent::__construct(AttributeCategoryIdentifier::RESOURCE);
        $this->addAttribute('resource:resource-id', $id);
        $this->addAttribute('resource:resource-type', $type);
    }
}