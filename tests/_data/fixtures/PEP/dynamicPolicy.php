<?php

$dynamicPolicy = [
    'policy' => [
        'ruleCombiningAlgorithmId' => 'rule-combining-algorithm:deny-overrides',
        'policyId'                 => 'dynamic:policy',
        'variableDefinition'       => [
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
        'rules'                    => [
            [
                'ruleId'      => 'dynamic:read',
                'effect'      => 'Permit',
                'description' => 'Subject can read resource',
                'condition'   => [
                    'variableReference' => [
                        'variableId' => 'resourceMatch',
                    ],
                ],
            ],
        ],
    ],
];