<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    AttributeIdentifier, CombiningAlgorithmIdentifier, DataTypeIdentifier, FunctionIdentifier
};

$policyName = 'resource-string-value-equals-policy';

return [
    'policy' => [
        'policyId'                 => "$policyName:policy",
        'ruleCombiningAlgorithmId' => CombiningAlgorithmIdentifier::DENY_OVERRIDES,
        'rules'                    => [
            [
                'ruleId'    => "$policyName:rule1",
                'effect'    => Decision::PERMIT,
                'condition' => [
                    'apply' => [
                        'description'                   => 'Resource can be accessed if string value matches',
                        'functionId'                    => FunctionIdentifier::STRING_EQUAL,
                        [
                            AttributeIdentifier::VALUE      => [
                                'dataType' => DataTypeIdentifier::STRING,
                                'text'     => 'John Smith',
                            ],
                        ],
                        [
                            'apply' => [
                                'functionId' => FunctionIdentifier::STRING_ONE_AND_ONLY,
                                [
                                    AttributeIdentifier::DESIGNATOR => [
                                        'category'      => AttributeIdentifier::RESOURCE_CATEGORY,
                                        'attributeId'   => 'document:client-name',
                                        'dataType'      => DataTypeIdentifier::STRING,
                                        'mustBePresent' => false,
                                    ],
                                ]
                            ]
                        ]
                    ],
                ],
            ],
        ],
    ],
];
