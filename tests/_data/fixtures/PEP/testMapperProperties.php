<?php

$testMapperProperties = [

    'factories' => [
//xacml.dataTypeFactory=org.apache.openaz.xacml.std.StdDataTypeFactory
//xacml.pdpEngineFactory=org.apache.openaz.xacml.pdp.OpenAZPDPEngineFactory
//xacml.pepEngineFactory=org.apache.openaz.xacml.std.pep.StdEngineFactory
//xacml.pipFinderFactory=org.apache.openaz.xacml.std.pip.StdPIPFinderFactory
//xacml.openaz.evaluationContextFactory=org.apache.openaz.xacml.pdp.std.StdEvaluationContextFactory
//xacml.openaz.functionDefinitionFactory=org.apache.openaz.xacml.pdp.std.StdFunctionDefinitionFactory
//xacml.openaz.policyFinderFactory=org.apache.openaz.xacml.pdp.std.StdPolicyFinderFactory

        'combiningAlgorithmFactory' => \Cerberus\PDP\Policy\CombiningAlgorithmFactory::class,
    ],
    'rootPolicies' => [
        'testPolicy' => [
            'file' => 'testPolicy',
        ]
    ],
    'pep' => [
        'issuer' => 'test',
        'mappers' => [
            'DocumentMapper', // class ? array
        ]
    ]

];