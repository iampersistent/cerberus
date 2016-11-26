<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\PDP\Utility\Properties;

class PepAgentFactory
{
    protected $pepAgent;


    public function __construct(Properties $properties)
    {

        $policyFinder = (new ArrayPolicyFinderFactory())->getPolicyFinder($testMapperProperties);
        $pipFinder = new PipFinder();
        Mock::double(CerberusEngine::class, ['describe' => true]);

        $pdpEngineFactory = $properties->get('factory.pdpEngineFactory');
        $pdpEngine = new $pdpEngineFactory($properties);



        require __DIR__ . '/../../_data/fixtures/PEP/testPolicy004.php';
        $mappingRegistry = new MapperRegistry($testPolicy004);
        $mappingRegistry->registerMapper(new DocumentMapper());
        $pepRequestFactory = new PepRequestFactory($mappingRegistry);

        $pepResponseFactory = new PepResponseFactory($mappingRegistry);
        $this->pepAgent = new PepAgent($pdpEngine, $pepRequestFactory, $pepResponseFactory);
    }

    public function getPepAgent(): PepAgent
    {
        return $this->pepAgent;
    }
}