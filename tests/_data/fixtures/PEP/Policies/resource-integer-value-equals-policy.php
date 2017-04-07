<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    AttributeIdentifier, CombiningAlgorithmIdentifier, DataTypeIdentifier, FunctionIdentifier
};

$policyName = 'resource-integer-value-equals-policy';

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
                        'description'                   => 'Resource can be accessed if integer value matches',
                        'functionId'                    => FunctionIdentifier::INTEGER_EQUAL,
                        [
                            AttributeIdentifier::VALUE      => [
                                'dataType' => DataTypeIdentifier::INTEGER,
                                'text'     => 123456,
                            ],
                        ],
                        [
                            'apply' => [
                                'functionId' => FunctionIdentifier::INTEGER_ONE_AND_ONLY,
                                [
                                    AttributeIdentifier::DESIGNATOR => [
                                        'category'      => AttributeIdentifier::RESOURCE_CATEGORY,
                                        'attributeId'   => 'document:document-size',
                                        'dataType'      => DataTypeIdentifier::INTEGER,
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
