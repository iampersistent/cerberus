<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\PDP\Contract\PdpEngine;
use Cerberus\PDP\Utility\Properties;

class PepAgentFactory
{
    /** @var PepConfig */
    protected $pepConfig;
    /** @var PdpEngine */
    protected $pdpEngine;
    /** @var Properties */
    protected $properties;


    public function __construct(Properties $properties)
    {
        // set content with database information for attributeSelector

        $this->properties = $properties;
        $pdpEngineFactory = $properties->get('factory.pdpEngine');
        $this->pdpEngine = (new $pdpEngineFactory())
            ->newEngine($properties);
        $this->pepConfig = new PepConfig($properties);
    }

    public function getPepAgent(): PepAgent
    {
        // todo: AZ lib had obligationHandlers
        $pepAgent = new PepAgent($this->properties, $this->pepConfig, $this->pdpEngine);

        return $pepAgent;
    }
}