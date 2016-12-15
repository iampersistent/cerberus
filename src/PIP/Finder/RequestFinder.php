<?php
declare(strict_types = 1);

namespace Cerberus\PIP\Finder;

use Cerberus\PIP\Engine\RequestEngine;
use Cerberus\PIP\PipFinder;
use Ds\Set;

class RequestFinder extends PipFinder
{
    protected $environmentEngine;
    protected $requestEngine;

    public function __construct(PipFinder $pipFinder, RequestEngine $requestEngine)
    {
        $this->requestEngine = $requestEngine;
        parent::__construct($pipFinder->getPipEngines());
    }

    public function getPipEngines(): Set
    {
        $engines = new Set($this->pipEngines);

        if ($this->requestEngine) {
            $engines->add($this->requestEngine);
        }
        if ($this->environmentEngine) {
            $engines->add($this->environmentEngine);
        }
        
        return $engines;
    }
}