<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    ActionIdentifier,
    AttributeCategoryIdentifier,
    CombiningAlgorithmIdentifier,
    ContextSelectorIdentifier,
    DataTypeIdentifier,
    FunctionIdentifier,
    ResourceIdentifier
};

return [
    'policy' => [
        'ruleCombiningAlgorithmId' => CombiningAlgorithmIdentifier::DENY_OVERRIDES,
        'policyId'                 => 'dynamic:policy',
        'variableDefinitions'      => [
            [
                'variableId' => 'resourceMatch',
                'apply'      => [
                    'functionId' => FunctionIdentifier::STRING_EQUAL,
                    [
                        'apply' => [
                            'functionId' => FunctionIdentifier::STRING_ONE_AND_ONLY,
                            [
                                'attributeDesignator' => [
                                    'attributeId'   => ResourceIdentifier::RESOURCE_TYPE,
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
                                'attributeSelector' => [
                                    'category'          => AttributeCategoryIdentifier::RESOURCE,
                                    'contextSelectorId' => ContextSelectorIdentifier::CONTENT_SELECTOR,
                                    'dataType'          => DataTypeIdentifier::STRING,
                                    'mustBePresent'     => false,
                                    'path'              => '$.resource.type',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'variableId' => 'actionMatch',
                'apply'      => [
                    'functionId' => FunctionIdentifier::STRING_IS_IN,
                    [
                        'apply' => [
                            'functionId' => FunctionIdentifier::STRING_ONE_AND_ONLY,
                            [
                                'attributeDesignator' => [
                                    'attributeId'   => ActionIdentifier::ACTION_ID,
                                    'category'      => AttributeCategoryIdentifier::ACTION,
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
                                'attributeSelector' => [
                                    'category'          => AttributeCategoryIdentifier::ACTION,
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
                'ruleId'      => 'permission:access',
                'effect'      => Decision::PERMIT,
                'description' => 'Subject can access resource',
                'condition'   => [
                    'variableReference' => [
                        'variableId' => 'resourceMatch',
                    ],
                ],
            ],
            [
                'ruleId'      => 'permission:action',
                'effect'      => Decision::PERMIT,
                'description' => 'Subject can perform action',
                'condition'   => [
                    'variableReference' => [
                        'variableId' => 'actionMatch',
                    ],
                ],
            ],
        ],
    ],
];
