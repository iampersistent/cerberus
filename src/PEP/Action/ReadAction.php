<?php
declare(strict_types = 1);

namespace Cerberus\PEP\Action;

class ReadAction extends Action
{
    public function __construct()
    {
        parent::__construct('read');
    }
}