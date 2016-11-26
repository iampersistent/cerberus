<?php

$testMapperProperties = [

    'factories' => [
//xacml.dataTypeFactory=org.apache.openaz.xacml.std.StdDataTypeFactory
//xacml.pipFinderFactory=org.apache.openaz.xacml.std.pip.StdPIPFinderFactory
//xacml.openaz.evaluationContextFactory=org.apache.openaz.xacml.pdp.std.StdEvaluationContextFactory
//xacml.openaz.functionDefinitionFactory=org.apache.openaz.xacml.pdp.std.StdFunctionDefinitionFactory
//xacml.openaz.policyFinderFactory=org.apache.openaz.xacml.pdp.std.StdPolicyFinderFactory

        'pdpEngine' => \Cerberus\PDP\CerberusEngineFactory::class,

        'combiningAlgorithm' => \Cerberus\PDP\Policy\CombiningAlgorithmFactory::class,
    ],
    'rootPolicies' => [
        'testPolicy' => [
            'type' => 'array',
            'file' => __DIR__ . 'testPolicy004.php',
        ]
    ],
    'pep' => [
        'issuer' => 'test',
        'mappers' => [
            'DocumentMapper', // class ? array
        ]
    ]

];