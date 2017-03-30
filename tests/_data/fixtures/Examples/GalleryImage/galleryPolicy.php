<?php

use Cerberus\PDP\Combiner\CombiningAlgorithm;
use Cerberus\PDP\Policy\Factory\FunctionDefinitionFactory;

return [
    'policy' => [
        'ruleCombiningAlgorithmId' => CombiningAlgorithm::DENY_UNLESS_PERMIT,
        'policyId'                 => 'gallery-images:policy',
        'variableDefinitions'      => [
            [
                'variableId' => 'imageGalleryIdAndActionMatch',
                'apply'      => [
                    'description' => 'make sure all of the checks evaluate to true',
                    'functionId'  => FunctionDefinitionFactory::BOOLEAN_ALL_OF,
                    [
                        'function' => [
                            'functionId' => FunctionDefinitionFactory::BOOLEAN_EQUAL,
                        ],
                    ],
                    [
                        'attributeValue' => [
                            'dataType' => 'boolean',
                            'text'     => true,
                        ],
                    ],
                    [
                        'apply' => [
                            'functionId' => FunctionDefinitionFactory::INTEGER_BAG,
                            [
                                'apply' => [
                                    'functionId'  => FunctionDefinitionFactory::ANY_OF_ANY,
                                    'description' => 'check to make sure the user has access to one of the galleries in the image',
                                    [
                                        'apply' => [
                                            'functionId' => FunctionDefinitionFactory::STRING_BAG,
                                            [
                                                'attributeSelector' => [
                                                    'category'          => 'attribute-category:resource',
                                                    'contextSelectorId' => 'image',
                                                    'dataType'          => 'string',
                                                    'mustBePresent'     => false,
                                                    'path'              => '$.resource.galleryIds',
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'apply' => [
                                            'functionId' => FunctionDefinitionFactory::STRING_BAG,
                                            [
                                                'attributeSelector' => [
                                                    'category'          => 'attribute-category:resource',
                                                    'contextSelectorId' => 'content-selector',
                                                    'dataType'          => 'string',
                                                    'mustBePresent'     => false,
                                                    'path'              => '$.resource.type',
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'apply' => [
                                            'functionId'  => FunctionDefinitionFactory::STRING_IS_IN,
                                            'description' => 'make sure the action has been permitted',
                                            [
                                                'apply' => [
                                                    'functionId' => FunctionDefinitionFactory::STRING_ONE_AND_ONLY,
                                                    [
                                                        'attributeDesignator' => [
                                                            'attributeId'   => 'action:action-id',
                                                            'category'      => 'attribute-category:action',
                                                            'dataType'      => 'string',
                                                            'mustBePresent' => false,
                                                        ],
                                                    ],
                                                ],
                                            ],
                                            [
                                                'apply' => [
                                                    'functionId' => FunctionDefinitionFactory::STRING_BAG,
                                                    [
                                                        'attributeSelector' => [
                                                            'category'          => 'attribute-category:action',
                                                            'contextSelectorId' => 'content-selector',
                                                            'dataType'          => 'string',
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
                    'functionId' => FunctionDefinitionFactory::STRING_IS_IN,
                    [
                        'apply' => [
                            'functionId' => FunctionDefinitionFactory::STRING_ONE_AND_ONLY,
                            [
                                'attributeDesignator' => [
                                    'attributeId'   => 'action:action-id',
                                    'category'      => 'attribute-category:action',
                                    'dataType'      => 'string',
                                    'mustBePresent' => false,
                                ],
                            ],
                        ],
                    ],
                    [
                        'apply' => [
                            'functionId' => FunctionDefinitionFactory::STRING_BAG,
                            [
                                'attributeSelector' => [
                                    'category'          => 'attribute-category:action',
                                    'contextSelectorId' => 'content-selector',
                                    'dataType'          => 'string',
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
                'effect'    => 'Permit',
                'target'    => [
                    [
                        'anyOf' => [
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'             => FunctionDefinitionFactory::STRING_EQUAL,
                                            'attributeValue'      => [
                                                'dataType' => 'string',
                                                'text'     => Image::class,
                                            ],
                                            'attributeDesignator' => [
                                                'category'      => 'attribute-category:resource',
                                                'attributeId'   => 'resource:resource-type',
                                                'dataType'      => 'string',
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
                                            'matchId'           => FunctionDefinitionFactory::STRING_EQUAL,
                                            'attributeValue'    => [
                                                'dataType' => 'string',
                                                'text'     => Gallery::class,
                                            ],
                                            'attributeSelector' => [
                                                'category'          => 'attribute-category:resource',
                                                'contextSelectorId' => 'content-selector',
                                                'dataType'          => 'string',
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
