<?php

use Cerberus\PDP\Combiner\CombiningAlgorithm;
use Cerberus\PDP\Policy\Factory\FunctionDefinitionFactory;

return [
    'policy' => [
        'ruleCombiningAlgorithmId' => CombiningAlgorithm::DENY_OVERRIDES,
        'policyId'                 => 'dynamic:policy',
        'variableDefinitions'      => [
            [
                'variableId' => 'resourceMatch',
                'apply'      => [
                    'functionId' => FunctionDefinitionFactory::STRING_EQUAL,
                    [
                        'apply' => [
                            'functionId' => FunctionDefinitionFactory::STRING_ONE_AND_ONLY,
                            [
                                'attributeDesignator' => [
                                    'attributeId'   => 'resource:resource-type',
                                    'category'      => 'attribute-category:resource',
                                    'dataType'      => 'string',
                                    'mustBePresent' => false,
                                ],
                            ],
                        ],
                    ],
                    [
                        'apply' => [
                            'functionId' => FunctionDefinitionFactory::STRING_ONE_AND_ONLY,
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
