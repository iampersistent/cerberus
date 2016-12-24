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
                        'functionId'        => 'function:string-one-and-only',
                        'attributeSelector' => [
                            'category'      => '',
                            'dataType'      => 'string',
                            'mustBePresent' => false,
                            'path'          => '',
                        ],
                    ],
                    [
                        'functionId'        => 'function:string-one-and-only',
                        'attributeSelector' => [
                            'category'      => '',
                            'dataType'      => 'string',
                            'mustBePresent' => false,
                            'path'          => '',
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