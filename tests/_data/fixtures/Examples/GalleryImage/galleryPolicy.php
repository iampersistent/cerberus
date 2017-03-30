<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    ActionIdentifier, AttributeCategoryIdentifier, ContextSelectorIdentifier, DataTypeIdentifier, ResourceIdentifier
};
use Cerberus\PDP\Combiner\CombiningAlgorithm;
use Cerberus\PDP\Policy\FunctionDefinition;

return [
    'policy' => [
        'ruleCombiningAlgorithmId' => CombiningAlgorithm::DENY_UNLESS_PERMIT,
        'policyId'                 => 'gallery-images:policy',
        'variableDefinitions'      => [
            [
                'variableId' => 'imageGalleryIdAndActionMatch',
                'apply'      => [
                    'description' => 'make sure all of the checks evaluate to true',
                    'functionId'  => FunctionDefinition::BOOLEAN_ALL_OF,
                    [
                        'function' => [
                            'functionId' => FunctionDefinition::BOOLEAN_EQUAL,
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
                            'functionId' => FunctionDefinition::INTEGER_BAG,
                            [
                                'apply' => [
                                    'functionId'  => FunctionDefinition::ANY_OF_ANY,
                                    'description' => 'check to make sure the user has access to one of the galleries in the image',
                                    [
                                        'apply' => [
                                            'functionId' => FunctionDefinition::STRING_BAG,
                                            [
                                                'attributeSelector' => [
                                                    'category'          => AttributeCategoryIdentifier::RESOURCE,
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
                                            'functionId' => FunctionDefinition::STRING_BAG,
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
                                    [
                                        'apply' => [
                                            'functionId'  => FunctionDefinition::STRING_IS_IN,
                                            'description' => 'make sure the action has been permitted',
                                            [
                                                'apply' => [
                                                    'functionId' => FunctionDefinition::STRING_ONE_AND_ONLY,
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
                                                    'functionId' => FunctionDefinition::STRING_BAG,
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
                            ],
                        ],
                    ],
                ],
            ],
            [
                'variableId' => 'actionMatch',
                'apply'      => [
                    'functionId' => FunctionDefinition::STRING_IS_IN,
                    [
                        'apply' => [
                            'functionId' => FunctionDefinition::STRING_ONE_AND_ONLY,
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
                            'functionId' => FunctionDefinition::STRING_BAG,
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
                'ruleId'    => 'gallery-image-access',
                'effect'    => Decision::PERMIT,
                'target'    => [
                    [
                        'anyOf' => [
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'             => FunctionDefinition::STRING_EQUAL,
                                            'attributeValue'      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => Image::class,
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
                                            'matchId'           => FunctionDefinition::STRING_EQUAL,
                                            'attributeValue'    => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => Gallery::class,
                                            ],
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
