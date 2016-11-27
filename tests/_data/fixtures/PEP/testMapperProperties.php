<?php

$testMapperProperties = [

    'factories'    => [
//xacml.dataTypeFactory=org.apache.openaz.xacml.std.StdDataTypeFactory
//xacml.openaz.evaluationContextFactory=org.apache.openaz.xacml.pdp.std.StdEvaluationContextFactory
//xacml.openaz.functionDefinitionFactory=org.apache.openaz.xacml.pdp.std.StdFunctionDefinitionFactory

        'combiningAlgorithm' => \Cerberus\PDP\Policy\CombiningAlgorithmFactory::class,
        'pdpEngine'          => \Cerberus\PDP\CerberusEngineFactory::class,
        'pipFinder'          => \Cerberus\PIP\PipFinderFactory::class,
        'policyFinder'       => \Cerberus\PDP\ArrayPolicyFinderFactory::class,
    ],
    'rootPolicies' => [
        __DIR__ . '/testPolicy004.php',
    ],
    'pep'          => [
        'issuer'  => 'test',
        'mappers' => [
            'classes' => [],
            'configurations' => [
                __DIR__ . '/documentMapper.php',
            ],
        ],
    ],

];