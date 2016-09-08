<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\StatusCode;
use Cerberus\PDP\Policy\Traits\PolicyComponent;

abstract class PolicySetChild
{
    use PolicyComponent;

    protected $identifier;

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    protected function validateComponent(): bool
    {
        if ($this->getIdentifier() == null) {
            $this->setStatus(StatusCode::STATUS_CODE_SYNTAX_ERROR(), "Missing identifier");

            return false;
        } else {
            $this->setStatus(StatusCode::STATUS_CODE_OK());

            return true;
        }
    }
}