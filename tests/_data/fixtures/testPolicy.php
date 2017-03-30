<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    ActionIdentifier, AttributeCategoryIdentifier, DataTypeIdentifier, ResourceIdentifier, SubjectCategoryIdentifier, SubjectIdentifier
};
use Cerberus\PDP\Combiner\CombiningAlgorithm;
use Cerberus\PDP\Policy\FunctionDefinition;

return [
    'policy' => [
        'ruleCombiningAlgorithmId' => CombiningAlgorithm::DENY_OVERRIDES,
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
                                            'matchId'             => FunctionDefinition::STRING_EQUAL,
                                            'attributeValue'      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'Julius Hibbert',
                                            ],
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
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'             => FunctionDefinition::STRING_EQUAL,
                                            'attributeValue'      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'http://medico.com/record/patient/BartSimpson',
                                            ],
                                            'attributeDesignator' => [
                                                'category'      => AttributeCategoryIdentifier::RESOURCE,
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
                                            'matchId'             => FunctionDefinition::STRING_EQUAL,
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
                                    [
                                        'match' => [
                                            'matchId'             => FunctionDefinition::STRING_EQUAL,
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
            ],
        ],
    ],
];
