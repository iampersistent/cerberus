<?php

return [
    'policy' => [
        'ruleCombiningAlgorithmId' => 'rule-combining-algorithm:deny-overrides',
        'policyId'                 => 'dynamic:policy',
        'variableDefinition'       => [
            'variableId' => 'resourceMatch',
            'apply'      => [
                'functionId' => 'function:string-equal',
                'apply'      => [
                    [
                        'functionId'                               => 'function:string-one-and-only',
                        'attributeDesignator or attributeSelector' => [
                            'category'      => '',
                            'dataType'      => 'string',
                            'mustBePresent' => false,
                        ],
                    ],
                    [
                        'functionId'                               => 'function:string-one-and-only',
                        'attributeDesignator or attributeSelector' => [
                            'category'      => '',
                            'dataType'      => 'string',
                            'mustBePresent' => false,
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