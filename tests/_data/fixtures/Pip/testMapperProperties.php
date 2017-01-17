<?php

$testMapperProperties = [

    'factories'    => [
//xacml.dataTypeFactory=org.apache.openaz.xacml.std.StdDataTypeFactory
//xacml.openaz.evaluationContextFactory=org.apache.openaz.xacml.pdp.std.StdEvaluationContextFactory

        'combiningAlgorithm' => Cerberus\PDP\Policy\CombiningAlgorithmFactory::class,
        'functionDefinition' => Cerberus\PDP\Policy\Factory\FunctionDefinitionFactory::class,
        'pdpEngine'          => Cerberus\PDP\CerberusEngineFactory::class,
        'pipFinder'          => Cerberus\PIP\Factory\PipFinderFactory::class,
        'policyFinder'       => Cerberus\PDP\ArrayPolicyFinderFactory::class,
    ],
    'rootPolicies' => [
        __DIR__ . '/dynamicPolicy.php',
    ],
    'pep'          => [
        'issuer'  => 'test',
        'mappers' => [
            'classes' => [],
            'configurations' => [
            ],
        ],
    ],

];