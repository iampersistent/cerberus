<?php

$testPolicy004 = [
    'policy' => [
        'policyId'                 => 'test004:policy',
        'ruleCombiningAlgorithmId' => 'rule-combining-algorithm:deny-overrides',
        'rules'                    => [
            [
                'ruleId'    => 'mapper-test:rule1',
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
                                                'text'     => 'ROLE_DOCUMENT_WRITER',
                                            ],
                                            'attributeDesignator' => [
                                                'category'      => 'subject-category:access-subject',
                                                'attributeId'   => 'subject:role-id',
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
                                            'matchId'             => 'function:string-equal',
                                            'attributeValue'      => [
                                                'dataType' => 'string',
                                                'text'     => 'Test\Document',
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
                                            'matchId'             => 'function:string-equal',
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
                'condition' => [
                    'apply' => [
                        'functionId' => 'function:string-equal',
                        [
                            'apply' => [
                                'functionId' => 'function:string-one-and-only',
                                [
                                    'attributeDesignator' => [
                                        'category'      => 'attribute-category:resource',
                                        'attributeId'   => 'document:document-owner',
                                        'dataType'      => 'string',
                                        'mustBePresent' => false,
                                    ],
                                ],
                            ],
                        ],
                        [
                            'apply' => [
                                'functionId' => 'function:string-one-and-only',
                                [
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
                ],
            ],
            [
                'ruleId'    => 'mapper-test:rule2',
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
                                                'text'     => 'ROLE_DOCUMENT_READER',
                                            ],
                                            'attributeDesignator' => [
                                                'category'      => 'subject-category:access-subject',
                                                'attributeId'   => 'subject:role-id',
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
                                            'matchId'             => 'function:string-equal',
                                            'attributeValue'      => [
                                                'dataType' => 'string',
                                                'text'     => 'Test\Document',
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
                                            'matchId'             => 'function:string-equal',
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
                                ],
                            ],
                        ],
                    ],
                ],
                'condition' => [
                    'apply' => [
                        'functionId' => 'function:string-equal',
                        [
                            'apply' => [
                                'functionId'          => 'function:string-one-and-only',
                                [
                                    'attributeDesignator' => [
                                        'attributeId'   => 'client:country-of-domicile',
                                        'category'      => 'attribute-category:resource',
                                        'dataType'      => 'string',
                                        'mustBePresent' => false,
                                    ],
                                ],
                            ],
                        ],
                        [
                            'apply' => [
                                'functionId'          => 'function:string-one-and-only',
                                [
                                    'attributeDesignator' => [
                                        'attributeId'   => 'request-context:country',
                                        'category'      => 'attribute-category:environment',
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
];
