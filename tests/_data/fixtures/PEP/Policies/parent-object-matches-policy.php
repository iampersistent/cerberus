<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    AttributeIdentifier, CombiningAlgorithmIdentifier, ContextSelectorIdentifier, DataTypeIdentifier, FunctionIdentifier, SubjectIdentifier
};

$policyId = 'parent-object-matches';

return [
    'policy' => [
        'policyId'                 => "$policyId:policy",
        'ruleCombiningAlgorithmId' => CombiningAlgorithmIdentifier::DENY_OVERRIDES,
        'rules'                    => [
            [
                'ruleId'    => "$policyId:rule1",
                'effect'    => Decision::PERMIT,
                'condition' => [
                    'apply'      => [
                        'description' => 'make sure all of the checks evaluate to true',
                        'functionId'  => FunctionIdentifier::BOOLEAN_ALL_OF,
                        [
                            'function' => [
                                'functionId' => FunctionIdentifier::BOOLEAN_EQUAL,
                            ],
                        ],
                        [
                            AttributeIdentifier::VALUE => [
                                'dataType' => DataTypeIdentifier::BOOLEAN,
                                'text'     => true,
                            ],
                        ],
                        [
                            'apply' => [
                                'functionId' => FunctionIdentifier::STRING_IS_IN,
                                [
                                    AttributeIdentifier::VALUE => [
                                        'dataType' => DataTypeIdentifier::STRING,
                                        'text'     => 42,
                                    ]
                                ],
                                [
                                    AttributeIdentifier::SELECTOR => [
                                        'category'          => AttributeIdentifier::RESOURCE_CATEGORY,
                                        'contextSelectorId' => 'childObject',
                                        'dataType'          => DataTypeIdentifier::STRING,
                                        'mustBePresent'     => false,
                                        'path'              => '$.resource.parentObjectIds',
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
