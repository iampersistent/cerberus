<?php

$dynamicPolicy = [
    'policy' => [
        'ruleCombiningAlgorithmId' => 'rule-combining-algorithm:deny-overrides',
        'policyId'                 => 'dynamic:policy',
        'variableDefinitions'      => [
            [
                'variableId' => 'resourceMatch',
                'apply'      => [
                    'functionId' => 'function:string-equal',
                    'apply'      => [
                        [
                            'functionId'          => 'function:string-one-and-only',
                            'attributeDesignator' => [
                                'attributeId'   => 'resource:resource-type',
                                'category'      => 'attribute-category:resource',
                                'dataType'      => 'string',
                                'mustBePresent' => false,
                            ],
                        ],
                        [
                            'functionId'        => 'function:string-one-and-only',
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
            [
                'variableId' => 'actionMatch',
                'apply'      => [
                    'functionId' => 'function:string-is-in',
                    'apply'      => [
                        [
                            'functionId'          => 'function:string-one-and-only',
                            'attributeDesignator' => [
                                'attributeId'   => 'action:action-id',
                                'category'      => 'attribute-category:action',
                                'dataType'      => 'string',
                                'mustBePresent' => false,
                            ],
                        ],
                        [
                            'functionId'        => 'function:string-bag',
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
        'rules'                    => [
            [
                'ruleId'      => 'permission:access',
                'effect'      => 'Permit',
                'description' => 'Subject can access resource',
                'condition'   => [
                    'variableReference' => [
                        'variableId' => 'resourceMatch',
                    ],
                ],
            ],
            [
                'ruleId'      => 'permission:action',
                'effect'      => 'Permit',
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