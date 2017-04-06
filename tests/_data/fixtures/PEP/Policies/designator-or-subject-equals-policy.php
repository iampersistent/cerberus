<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    ActionIdentifier, AttributeIdentifier, CombiningAlgorithmIdentifier, ContextSelectorIdentifier, DataTypeIdentifier, FunctionIdentifier, ResourceIdentifier
};

$policyId = 'designator-or-subject-equals';

return [
    'policy' => [
        'policyId'                 => "$policyId:policy",
        'ruleCombiningAlgorithmId' => CombiningAlgorithmIdentifier::DENY_OVERRIDES,
        'variableDefinitions' => [
            [
                'variableId' => 'actionMatch',
                'apply' => [
                    'functionId'  => FunctionIdentifier::STRING_IS_IN,
                    'description' => 'make sure the action has been permitted',
                    [
                        'apply' => [
                            'functionId' => FunctionIdentifier::STRING_ONE_AND_ONLY,
                            [
                                AttributeIdentifier::DESIGNATOR => [
                                    'attributeId'   => ActionIdentifier::ACTION_ID,
                                    'category'      => AttributeIdentifier::ACTION_CATEGORY,
                                    'dataType'      => DataTypeIdentifier::STRING,
                                    'mustBePresent' => false,
                                ],
                            ],
                        ],
                    ],
                    [
                        'apply' => [
                            'functionId' => FunctionIdentifier::STRING_BAG,
                            [
                                AttributeIdentifier::SELECTOR => [
                                    'category'          => AttributeIdentifier::ACTION_CATEGORY,
                                    'contextSelectorId' => ContextSelectorIdentifier::CONTENT_SELECTOR,
                                    'dataType'          => DataTypeIdentifier::STRING,
                                    'mustBePresent'     => false,
                                    'path'              => '$.actions',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'rules'                    => [
            [
                'ruleId' => "$policyId:rule1",
                'effect' => Decision::PERMIT,
                'target' => [
                    [
                        'anyOf' => [
//                            [
//                                'description' => 'allow if document is public',
//                                'allOf'       => [
//                                    [
//                                        'match' => [
//                                            'matchId'                       => FunctionIdentifier::BOOLEAN_EQUAL,
//                                            AttributeIdentifier::VALUE      => [
//                                                'dataType' => DataTypeIdentifier::BOOLEAN,
//                                                'text'     => true,
//                                            ],
//                                            AttributeIdentifier::DESIGNATOR => [
//                                                'category'      => AttributeIdentifier::RESOURCE_CATEGORY,
//                                                'attributeId'   => 'document:is-public',
//                                                'dataType'      => DataTypeIdentifier::BOOLEAN,
//                                                'mustBePresent' => false,
//                                            ],
//                                        ],
//                                    ],
//                                    [
//                                        'match' => [
//                                            'matchId'                       => FunctionIdentifier::STRING_EQUAL,
//                                            AttributeIdentifier::VALUE      => [
//                                                'dataType' => DataTypeIdentifier::STRING,
//                                                'text'     => ActionIdentifier::READ,
//                                            ],
//                                            AttributeIdentifier::DESIGNATOR => [
//                                                'category'      => AttributeIdentifier::ACTION_CATEGORY,
//                                                'attributeId'   => ActionIdentifier::ACTION_ID,
//                                                'dataType'      => DataTypeIdentifier::STRING,
//                                                'mustBePresent' => false,
//                                            ],
//                                        ],
//                                    ],
//                                ],
//                            ],
                            [
                                'allOf' => [
//                                    [
//                                        'match' => [
//                                            'matchId'                       => FunctionIdentifier::STRING_EQUAL,
//                                            AttributeIdentifier::DESIGNATOR => [
//                                                'category'      => AttributeIdentifier::RESOURCE_CATEGORY,
//                                                'attributeId'   => ResourceIdentifier::RESOURCE_TYPE,
//                                                'dataType'      => DataTypeIdentifier::STRING,
//                                                'mustBePresent' => false,
//                                            ],
//                                            AttributeIdentifier::SELECTOR   => [
//                                                'category'          => AttributeIdentifier::RESOURCE_CATEGORY,
//                                                'contextSelectorId' => ContextSelectorIdentifier::CONTENT_SELECTOR,
//                                                'dataType'          => DataTypeIdentifier::STRING,
//                                                'mustBePresent'     => false,
//                                                'path'              => '$.resource.type',
//                                            ],
//                                        ],
//                                    ],
                                    [
                                        'match' => [
                                            'matchId'                       => FunctionIdentifier::STRING_IS_IN,
                                            AttributeIdentifier::DESIGNATOR => [
                                                'category'      => AttributeIdentifier::ACTION_CATEGORY,
                                                'attributeId'   => ActionIdentifier::ACTION_ID,
                                                'dataType'      => DataTypeIdentifier::STRING,
                                                'mustBePresent' => false,
                                            ],
                                            AttributeIdentifier::SELECTOR   => [
                                                'category'          => AttributeIdentifier::ACTION_CATEGORY,
                                                'contextSelectorId' => ContextSelectorIdentifier::CONTENT_SELECTOR,
                                                'dataType'          => DataTypeIdentifier::STRING,
                                                'mustBePresent'     => false,
                                                'path'              => '$.actions',
                                            ],
                                        ],
                                    ],
                                ],
//                                'condition' => [
//                                    'variableReference' => [
//                                        'variableId' => 'actionMatch',
//                                    ],
//                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];