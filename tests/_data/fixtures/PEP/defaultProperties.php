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
    'contentSelector' => [
        'classes' => [
            'mapper'     => Cerberus\PEP\PersistedResourceMapper::class,
            'manager'    => Cerberus\PIP\Permission\PermissionManager::class,
            'repository' => Cerberus\PIP\Permission\PermissionMemoryRepository::class,
        ],
        'config'  => [
            'repository' => [],
        ],
    ],
];