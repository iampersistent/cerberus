<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    AttributeIdentifier, CombiningAlgorithmIdentifier, DataTypeIdentifier, FunctionIdentifier
};

$policyName = 'resource-boolean-value-equals-policy';

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
                        'description'                   => 'Resource can be accessed if boolean value matches',
                        'functionId'                    => FunctionIdentifier::BOOLEAN_EQUAL,
                        [
                            AttributeIdentifier::VALUE      => [
                                'dataType' => DataTypeIdentifier::BOOLEAN,
                                'text'     => true,
                            ],
                        ],
                        [
                            'apply' => [
                                'functionId' => FunctionIdentifier::BOOLEAN_ONE_AND_ONLY,
                                [
                                    AttributeIdentifier::DESIGNATOR => [
                                        'category'      => AttributeIdentifier::RESOURCE_CATEGORY,
                                        'attributeId'   => 'document:is-public',
                                        'dataType'      => DataTypeIdentifier::BOOLEAN,
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
