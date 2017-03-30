<?php

return [
    'policy' => [
        'ruleCombiningAlgorithmId' => CombiningAlgorithm::DENY_OVERRIDES,
        'policyId'                 => 'dynamic:policy',
        'variableDefinition'       => [
            'variableId' => 'resourceMatch',
            'apply'      => [
                'functionId' => FunctionDefinitionFactory::STRING_EQUAL,
                'apply'      => [
                    [
                        'functionId'                               => FunctionDefinitionFactory::STRING_ONE_AND_ONLY,
                        'attributeDesignator or attributeSelector' => [
                            'category'      => '',
                            'dataType'      => 'string',
                            'mustBePresent' => false,
                        ],
                    ],
                    [
                        'functionId'                               => FunctionDefinitionFactory::STRING_ONE_AND_ONLY,
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