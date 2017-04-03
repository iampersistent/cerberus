<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    ActionIdentifier,
    AttributeIdentifier,
    CombiningAlgorithmIdentifier,
    ContextSelectorIdentifier,
    DataTypeIdentifier,
    FunctionIdentifier,
    ResourceIdentifier
};

return [
    'policy' => [
        'ruleCombiningAlgorithmId' => CombiningAlgorithmIdentifier::DENY_OVERRIDES,
        'policyId'                 => 'gallery-images:policy',
        'variableDefinitions'      => [
            [
                'variableId' => 'imageGalleryIdAndActionMatch',
                'apply'      => [
                    'description' => 'make sure all of the checks evaluate to true',
                    'functionId'  => FunctionIdentifier::BOOLEAN_ALL_OF,
                    [
                        'function' => [
                            'functionId' => FunctionIdentifier::BOOLEAN_EQUAL,
                        ],
                    ],
                    [
                        'attributeValue' => [
                            'dataType' => DataTypeIdentifier::BOOLEAN,
                            'text'     => true,
                        ],
                    ],
                    [
                        'apply' => [
                            'functionId' => FunctionIdentifier::INTEGER_BAG,
                            [
                                'apply' => [
                                    'functionId'  => FunctionIdentifier::ANY_OF_ANY,
                                    'description' => 'check to make sure the user has access to one of the galleries in the image',
                                    [
                                        'apply' => [
                                            'functionId' => FunctionIdentifier::STRING_BAG,
                                            [
                                                'attributeSelector' => [
                                                    'category'          => AttributeIdentifier::RESOURCE_CATEGORY,
                                                    'contextSelectorId' => 'image',
                                                    'dataType'          => DataTypeIdentifier::STRING,
                                                    'mustBePresent'     => false,
                                                    'path'              => '$.resource.galleryIds',
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'apply' => [
                                            'functionId' => FunctionIdentifier::STRING_BAG,
                                            [
                                                'attributeSelector' => [
                                                    'category'          => AttributeIdentifier::RESOURCE_CATEGORY,
                                                    'contextSelectorId' => ContextSelectorIdentifier::CONTENT_SELECTOR,
                                                    'dataType'          => DataTypeIdentifier::STRING,
                                                    'mustBePresent'     => false,
                                                    'path'              => '$.resource.type',
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'apply' => [
                                            'functionId'  => FunctionIdentifier::STRING_IS_IN,
                                            'description' => 'make sure the action has been permitted',
                                            [
                                                'apply' => [
                                                    'functionId' => FunctionIdentifier::STRING_ONE_AND_ONLY,
                                                    [
                                                        'attributeDesignator' => [
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
                                                        'attributeSelector' => [
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
                                'attributeSelector' => [
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
                'ruleId'    => 'gallery-image-access',
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
                                                'text'     => Image::class,
                                            ],
                                            'attributeDesignator' => [
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
                                            'matchId'           => FunctionIdentifier::STRING_EQUAL,
                                            'attributeValue'    => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => Gallery::class,
                                            ],
                                            'attributeSelector' => [
                                                'category'          => AttributeIdentifier::RESOURCE_CATEGORY,
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
                    ], #anyOf
                ],
                'condition' => [
                    'variableReference' => [
                        'variableId' => 'imageGalleryIdAndActionMatch',
                    ],
                ],
            ],
        ],
    ],
];
