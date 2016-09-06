<?php
declare(strict_types = 1);

namespace Cerberus\PDP;

use Cerberus\Core\Response;
use Cerberus\Core\Result;
use Cerberus\Core\Status;

class MutableResponse extends Response
{
    public function __construct($result = null)
    {
        parent::__construct(); // needs to happen first to initialize result set

        if ($result instanceof Status) {
            $this->add(new MutableResult($result));
        }
        if ($result instanceof Result) {
            $this->add($result);
        }
    }
}