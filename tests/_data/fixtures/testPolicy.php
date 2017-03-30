<?php

use Cerberus\PDP\Combiner\CombiningAlgorithm;
use Cerberus\PDP\Policy\Factory\FunctionDefinitionFactory;

return [
    'policy' => [
        'ruleCombiningAlgorithmId' => CombiningAlgorithm::DENY_OVERRIDES,
        'policyId'                 => 'test001:policy',
        'rules'                    => [
            [
                'ruleId'      => 'test001:rule-1',
                'effect'      => 'Permit',
                'description' => "Julius Hibbert can read or write Bart Simpson's medical record.",
                'target'      => [
                    [
                        'anyOf' => [
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'             => FunctionDefinitionFactory::STRING_EQUAL,
                                            'attributeValue'      => [
                                                'dataType' => 'string',
                                                'text'     => 'Julius Hibbert',
                                            ],
                                            'attributeDesignator' => [
                                                'category'      => 'subject-category:access-subject',
                                                'attributeId'   => 'subject:subject-id',
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
                                            'matchId'             => FunctionDefinitionFactory::STRING_EQUAL,
                                            'attributeValue'      => [
                                                'dataType' => 'string',
                                                'text'     => 'http://medico.com/record/patient/BartSimpson',
                                            ],
                                            'attributeDesignator' => [
                                                'category'      => 'attribute-category:resource',
                                                'attributeId'   => 'resource:resource-id',
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
                                            'matchId'             => FunctionDefinitionFactory::STRING_EQUAL,
                                            'attributeValue'      => [
                                                'dataType' => 'string',
                                                'text'     => 'read',
                                            ],
                                            'attributeDesignator' => [
                                                'category'      => 'attribute-category:action',
                                                'attributeId'   => 'action:action-id',
                                                'dataType'      => 'string',
                                                'mustBePresent' => false,
                                            ],
                                        ],
                                    ],
                                    [
                                        'match' => [
                                            'matchId'             => FunctionDefinitionFactory::STRING_EQUAL,
                                            'attributeValue'      => [
                                                'dataType' => 'string',
                                                'text'     => 'write',
                                            ],
                                            'attributeDesignator' => [
                                                'category'      => 'attribute-category:action',
                                                'attributeId'   => 'action:action-id',
                                                'dataType'      => 'string',
                                                'mustBePresent' => false,
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
];
