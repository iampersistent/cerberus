<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    ActionIdentifier,
    AttributeIdentifier,
    CombiningAlgorithmIdentifier,
    DataTypeIdentifier,
    FunctionIdentifier,
    ResourceIdentifier,
    SubjectIdentifier
};

return [
    'policy' => [
        'policyId'                 => 'test004:policy',
        'ruleCombiningAlgorithmId' => CombiningAlgorithmIdentifier::DENY_OVERRIDES,
        'rules'                    => [
            [
                'ruleId'    => 'mapper-test:rule1',
                'effect'    => Decision::PERMIT,
                'target'    => [
                    [
                        'anyOf' => [
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'                       => FunctionIdentifier::STRING_EQUAL,
                                            AttributeIdentifier::VALUE      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'ROLE_DOCUMENT_WRITER',
                                            ],
                                            AttributeIdentifier::DESIGNATOR => [
                                                'category'      => SubjectIdentifier::ACCESS_SUBJECT_CATEGORY,
                                                'attributeId'   => 'SubjectIdentifier::ROLE_ID',
                                                'dataType'      => DataTypeIdentifier::STRING,
                                                'mustBePresent' => false,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'                       => FunctionIdentifier::STRING_EQUAL,
                                            AttributeIdentifier::VALUE      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'TestData\Document',
                                            ],
                                            AttributeIdentifier::DESIGNATOR => [
                                                'category'      => AttributeIdentifier::RESOURCE_CATEGORY,
                                                'attributeId'   => ResourceIdentifier::RESOURCE_TYPE,
                                                'dataType'      => DataTypeIdentifier::STRING,
                                                'mustBePresent' => false,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'                       => FunctionIdentifier::STRING_EQUAL,
                                            AttributeIdentifier::VALUE      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'write',
                                            ],
                                            AttributeIdentifier::DESIGNATOR => [
                                                'category'      => AttributeIdentifier::ACTION_CATEGORY,
                                                'attributeId'   => ActionIdentifier::ACTION_ID,
                                                'dataType'      => DataTypeIdentifier::STRING,
                                                'mustBePresent' => false,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'condition' => [
                    'apply' => [
                        'functionId' => FunctionIdentifier::STRING_EQUAL,
                        [
                            'apply' => [
                                'functionId' => FunctionIdentifier::STRING_ONE_AND_ONLY,
                                [
                                    AttributeIdentifier::DESIGNATOR => [
                                        'category'      => AttributeIdentifier::RESOURCE_CATEGORY,
                                        'attributeId'   => 'document:document-owner',
                                        'dataType'      => DataTypeIdentifier::STRING,
                                        'mustBePresent' => false,
                                    ],
                                ],
                            ],
                        ],
                        [
                            'apply' => [
                                'functionId' => FunctionIdentifier::STRING_ONE_AND_ONLY,
                                [
                                    AttributeIdentifier::DESIGNATOR => [
                                        'category'      => SubjectIdentifier::ACCESS_SUBJECT_CATEGORY,
                                        'attributeId'   => SubjectIdentifier::SUBJECT_ID,
                                        'dataType'      => DataTypeIdentifier::STRING,
                                        'mustBePresent' => false,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'ruleId'    => 'mapper-test:rule2',
                'effect'    => Decision::PERMIT,
                'target'    => [
                    [
                        'anyOf' => [
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'                       => FunctionIdentifier::STRING_EQUAL,
                                            AttributeIdentifier::VALUE      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'ROLE_DOCUMENT_READER',
                                            ],
                                            AttributeIdentifier::DESIGNATOR => [
                                                'category'      => SubjectIdentifier::ACCESS_SUBJECT_CATEGORY,
                                                'attributeId'   => 'SubjectIdentifier::ROLE_ID',
                                                'dataType'      => DataTypeIdentifier::STRING,
                                                'mustBePresent' => false,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'                       => FunctionIdentifier::STRING_EQUAL,
                                            AttributeIdentifier::VALUE      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'TestData\Document',
                                            ],
                                            AttributeIdentifier::DESIGNATOR => [
                                                'category'      => AttributeIdentifier::RESOURCE_CATEGORY,
                                                'attributeId'   => ResourceIdentifier::RESOURCE_TYPE,
                                                'dataType'      => DataTypeIdentifier::STRING,
                                                'mustBePresent' => false,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'                       => FunctionIdentifier::STRING_EQUAL,
                                            AttributeIdentifier::VALUE      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'read',
                                            ],
                                            AttributeIdentifier::DESIGNATOR => [
                                                'category'      => AttributeIdentifier::ACTION_CATEGORY,
                                                'attributeId'   => ActionIdentifier::ACTION_ID,
                                                'dataType'      => DataTypeIdentifier::STRING,
                                                'mustBePresent' => false,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'condition' => [
                    'apply' => [
                        'functionId' => FunctionIdentifier::STRING_EQUAL,
                        [
                            'apply' => [
                                'functionId' => FunctionIdentifier::STRING_ONE_AND_ONLY,
                                [
                                    AttributeIdentifier::DESIGNATOR => [
                                        'attributeId'   => 'client:country-of-domicile',
                                        'category'      => AttributeIdentifier::RESOURCE_CATEGORY,
                                        'dataType'      => DataTypeIdentifier::STRING,
                                        'mustBePresent' => false,
                                    ],
                                ],
                            ],
                        ],
                        [
                            'apply' => [
                                'functionId' => FunctionIdentifier::STRING_ONE_AND_ONLY,
                                [
                                    AttributeIdentifier::DESIGNATOR => [
                                        'attributeId'   => 'request-context:country',
                                        'category'      => 'attribute-category:environment',
                                        'dataType'      => DataTypeIdentifier::STRING,
                                        'mustBePresent' => false,
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
