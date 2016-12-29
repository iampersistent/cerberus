<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\ObligationRouter;
use Cerberus\Core\Result;

class PepResponseFactory
{
    protected $obligationRouter;

    public function __construct($obligationRouter) // ObligationRouter
    {
        $this->obligationRouter = $obligationRouter;
    }

    public function newPepResponse(Result $result): PepResponse
    {
        return new PepResponse($this->obligationRouter, $result);
    }
}