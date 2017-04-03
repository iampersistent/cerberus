<?php

return [
    'factories'    => [
        'combiningAlgorithm' => Cerberus\PDP\Policy\CombiningAlgorithmFactory::class,
        'functionDefinition' => Cerberus\PDP\Policy\Factory\FunctionDefinitionFactory::class,
        'pdpEngine'          => Cerberus\PDP\CerberusEngineFactory::class,
        'pipFinder'          => Cerberus\PIP\Factory\PipFinderFactory::class,
        'policyFinder'       => Cerberus\PDP\ArrayPolicyFinderFactory::class,
    ],
    'rootPolicies' => [],
    'pep'          => [
        'issuer'  => 'test',
        'mappers' => [
            'classes' => [],
            'configurations' => [],
        ],
    ],
];