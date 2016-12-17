<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Map;

class ObligationStore
{
    protected $obligationMapContainer;

    public function __construct()
    {
        $this->obligationMapContainer = new Map();
    }

    public function clear()
    {
        $this->obligationMapContainer->clear();
    }
}