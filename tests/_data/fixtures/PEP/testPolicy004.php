<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    ActionIdentifier,
    AttributeCategoryIdentifier,
    CombiningAlgorithmIdentifier,
    DataTypeIdentifier,
    FunctionIdentifier,
    ResourceIdentifier,
    SubjectCategoryIdentifier,
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
                                            'matchId'             => FunctionIdentifier::STRING_EQUAL,
                                            'attributeValue'      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'ROLE_DOCUMENT_WRITER',
                                            ],
                                            'attributeDesignator' => [
                                                'category'      => SubjectCategoryIdentifier::ACCESS_SUBJECT,
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
                                            'matchId'             => FunctionIdentifier::STRING_EQUAL,
                                            'attributeValue'      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'Test\Document',
                                            ],
                                            'attributeDesignator' => [
                                                'category'      => AttributeCategoryIdentifier::RESOURCE,
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
                                            'matchId'             => FunctionIdentifier::STRING_EQUAL,
                                            'attributeValue'      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'write',
                                            ],
                                            'attributeDesignator' => [
                                                'category'      => AttributeCategoryIdentifier::ACTION,
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
                                    'attributeDesignator' => [
                                        'category'      => AttributeCategoryIdentifier::RESOURCE,
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
                                    'attributeDesignator' => [
                                        'category'      => SubjectCategoryIdentifier::ACCESS_SUBJECT,
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
                                            'matchId'             => FunctionIdentifier::STRING_EQUAL,
                                            'attributeValue'      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'ROLE_DOCUMENT_READER',
                                            ],
                                            'attributeDesignator' => [
                                                'category'      => SubjectCategoryIdentifier::ACCESS_SUBJECT,
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
                                            'matchId'             => FunctionIdentifier::STRING_EQUAL,
                                            'attributeValue'      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'Test\Document',
                                            ],
                                            'attributeDesignator' => [
                                                'category'      => AttributeCategoryIdentifier::RESOURCE,
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
                                            'matchId'             => FunctionIdentifier::STRING_EQUAL,
                                            'attributeValue'      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'read',
                                            ],
                                            'attributeDesignator' => [
                                                'category'      => AttributeCategoryIdentifier::ACTION,
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
                                    'attributeDesignator' => [
                                        'attributeId'   => 'client:country-of-domicile',
                                        'category'      => AttributeCategoryIdentifier::RESOURCE,
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
                                    'attributeDesignator' => [
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
