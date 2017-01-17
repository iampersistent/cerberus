<?php

return [
    'policy' => [
        'ruleCombiningAlgorithmId' => 'rule-combining-algorithm:deny-unless-permit',
        'policyId'                 => 'gallery-images:policy',
        'variableDefinitions'      => [
            [
                'variableId' => 'imageGalleryIdAndActionMatch',
                'apply'      => [
                    'description' => 'make sure all of the checks evaluate to true',
                    'functionId'  => 'function:boolean-all-of',
                    [
                        'function' => [
                            'functionId' => 'function:boolean-equal',
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
                            'functionId' => 'function:integer-bag',
                            [
                                'apply' => [
                                    'functionId'  => 'function:any-of-any',
                                    'description' => 'check to make sure the user has access to one of the galleries in the image',
                                    [
                                        'apply' => [
                                            'functionId' => 'function:string-bag',
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
                                            'functionId' => 'function:string-bag',
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
                                            'functionId'  => 'function:string-is-in',
                                            'description' => 'make sure the action has been permitted',
                                            [
                                                'apply' => [
                                                    'functionId' => 'function:string-one-and-only',
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
                                                    'functionId' => 'function:string-bag',
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
                    'functionId' => 'function:string-is-in',
                    [
                        'apply' => [
                            'functionId' => 'function:string-one-and-only',
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
                            'functionId' => 'function:string-bag',
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
                                            'matchId'             => 'function:string-equal',
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
                                            'matchId'           => 'function:string-equal',
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
