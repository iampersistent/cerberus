<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Cerberus\Core\Status;
use Cerberus\Core\StatusCode;

class ExpressionResultBag extends ExpressionResult
{
    protected $bag;

    public function __construct(Bag $bag)
    {
        $this->bag = $bag;

        parent::__construct(Status::createOk());
    }

    public function isBag(): bool
    {
        return true;
    }
}