<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    AttributeIdentifier, CombiningAlgorithmIdentifier, DataTypeIdentifier, FunctionIdentifier
};

$policyId = 'and-equality';

return [
    'policy' => [
        'policyId'                 => "$policyId:policy",
        'ruleCombiningAlgorithmId' => CombiningAlgorithmIdentifier::DENY_OVERRIDES,
        'rules'                    => [
            [
                'ruleId'    => "$policyId:rule1",
                'effect'    => Decision::PERMIT,
                'condition' => [
                    'apply' => [
                        'functionId' => FunctionIdentifier::AND,
                        [
                            'apply' => [
                                'description' => 'allow if document is public',
                                'functionId'  => FunctionIdentifier::BOOLEAN_EQUAL,
                                [
                                    AttributeIdentifier::VALUE => [
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
                                    ],
                                ],
                            ],
                        ],
                        [
                            'apply' => [
                                'description' => 'allow if document size matches',
                                'functionId'  => FunctionIdentifier::INTEGER_EQUAL,
                                [
                                    AttributeIdentifier::VALUE => [
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
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];