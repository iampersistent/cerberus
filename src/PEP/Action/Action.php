<?php
declare(strict_types = 1);

namespace Cerberus\PEP\Action;

use Cerberus\Core\Enums\AttributeCategoryIdentifier;
use Cerberus\PEP\CategoryContainer;

class Action extends CategoryContainer
{
    protected $actionIdValue;

    public function __construct(string $actionIdValue)
    {
        $this->actionIdValue = $actionIdValue;
        parent::__construct(AttributeCategoryIdentifier::ACTION);
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