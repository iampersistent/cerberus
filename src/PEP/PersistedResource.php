<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Identifier;

class PersistedResource extends CategoryContainer
{
    protected $id;
    protected $type;

    public function __construct(...$objects)
    {
        $this->id = 'persisted-resource';
        $this->type = Identifier::DATATYPE_XPATH_EXPRESSION;
        parent::__construct(Identifier::CONTENT_SELECTOR);
    }
}