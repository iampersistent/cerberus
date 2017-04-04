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
        'ruleCombiningAlgorithmId' => CombiningAlgorithmIdentifier::DENY_OVERRIDES,
        'policyId'                 => 'test001:policy',
        'rules'                    => [
            [
                'ruleId'      => 'test001:rule-1',
                'effect'      => Decision::PERMIT,
                'description' => "Julius Hibbert can read or write Bart Simpson's medical record.",
                'target'      => [
                    [
                        'anyOf' => [
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'                       => FunctionIdentifier::STRING_EQUAL,
                                            AttributeIdentifier::VALUE      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'Julius Hibbert',
                                            ],
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
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'                       => FunctionIdentifier::STRING_EQUAL,
                                            AttributeIdentifier::VALUE      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'http://medico.com/record/patient/BartSimpson',
                                            ],
                                            AttributeIdentifier::DESIGNATOR => [
                                                'category'      => AttributeIdentifier::RESOURCE_CATEGORY,
                                                'attributeId'   => ResourceIdentifier::RESOURCE_ID,
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
            ],
        ],
    ],
];
