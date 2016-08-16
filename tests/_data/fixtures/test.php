<?php

$policy = [
    'policy' => [
        'policyId' => 'urn:oasis:names:tc:xacml:2.0:test001:policy',
        'rule'     => [
            'ruleId'      => 'urn:oasis:names:tc:xacml:1.0:test001:rule-1',
            'effect'      => 'Permit',
            'description' => "Julius Hibbert can read or write Bart Simpson's medical record.",
            'target'      => [
                'anyOf' => [
                    [
                        'allOf' => [
                            'match' => [
                                'matchId'             => 'function:string-equal',
                                'attributeValue'      => [
                                    'dataType' => 'string',
                                    'text'     => 'Julius Hibbert',
                                ],
                                'attributeDesignator' => [
                                    'category'       => 'subject-category:access-subject',
                                    'attributeId'   => 'subject:subject-id',
                                    'dataType'      => 'string',
                                    'mustBePresent' => false,
                                ],
                            ],
                        ],
                    ],
                    [
                        'allOf' => [
                            'match' => [
                                'matchId'             => 'function:string-equal',
                                'attributeValue'      => [
                                    'dataType' => 'string',
                                    'text'     => 'http://medico.com/record/patient/BartSimpson',
                                ],
                                'attributeDesignator' => [
                                    'category'      => 'attribute-category:resource',
                                    'attributeId'   => 'source:resource-id',
                                    'dataType'      => 'string',
                                    'mustBePresent' => false,
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
    ],
];