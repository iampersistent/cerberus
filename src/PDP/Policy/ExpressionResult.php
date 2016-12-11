<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

class ExpressionResult
{
    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function isBag()
    {
        return false;
    }
}