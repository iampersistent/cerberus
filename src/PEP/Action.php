<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Identifier;

class Action extends CategoryContainer
{
    protected $actionIdValue;

    public function __construct(string $actionIdValue)
    {
        $this->actionIdValue = $actionIdValue;
        parent::__construct(Identifier::ATTRIBUTE_CATEGORY_ACTION);
        $this->addAttribute('action:action-id', $actionIdValue);
    }

    /**
     * Get the value for default attribute.
     */
    public function getActionIdValue(): string
    {
        return $this->actionIdValue;
    }
}