<?php

$properties = [

    'factories'       => [
        'combiningAlgorithm' => \Cerberus\PDP\Policy\CombiningAlgorithmFactory::class,
        'functionDefinition' => \Cerberus\PDP\Policy\Factory\FunctionDefinitionFactory::class,
        'pdpEngine'          => \Cerberus\PDP\CerberusEngineFactory::class,
        'pipFinder'          => \Cerberus\PIP\Factory\PipFinderFactory::class,
        'policyFinder'       => \Cerberus\PDP\ArrayPolicyFinderFactory::class,
    ],
    'rootPolicies'    => [
        __DIR__ . '/galleryPolicy.php',
    ],
    'pep'             => [
        'issuer'  => 'test',
        'mappers' => [
            'classes'        => [
                ImageMapper::class,
            ],
            'configurations' => [
                __DIR__ . '/galleryMapper.php',
            ],
        ],
    ],
    'contentSelector' => [
        'classes' => [
            'mapper'     => \Cerberus\PEP\PersistedResourceMapper::class,
            'manager'    => \Cerberus\PIP\Permission\PermissionManager::class,
            'repository' => \Cerberus\PIP\Permission\PermissionMemoryRepository::class,
        ],
        'config'  => [
            'repository' => [],
        ],
    ],
];
