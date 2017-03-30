<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Enums\{
    ContextSelectorIdentifier, DataTypeIdentifier
};

class PersistedResource extends CategoryContainer
{
    protected $id;
    protected $type;

    public function __construct(...$objects)
    {
        $this->id = 'persisted-resource';
        $this->type = DataTypeIdentifier::XPATH_EXPRESSION;
        parent::__construct(ContextSelectorIdentifier::CONTENT_SELECTOR);
    }
}