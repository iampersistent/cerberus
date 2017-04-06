<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    AttributeIdentifier, CombiningAlgorithmIdentifier, ContextSelectorIdentifier, DataTypeIdentifier, FunctionIdentifier, SubjectIdentifier
};

$policyId = 'document-id-equals';

return [
    'policy' => [
        'policyId'                 => "$policyId:policy",
        'ruleCombiningAlgorithmId' => CombiningAlgorithmIdentifier::DENY_OVERRIDES,
        'rules'                    => [
            [
                'ruleId'    => "$policyId:rule1",
                'effect'    => Decision::PERMIT,
                'condition' => [
                    'apply' => [
                        'description' => 'id of resource must equal "42"',
                        'functionId'  => FunctionIdentifier::INTEGER_EQUAL,
                        [
                            AttributeIdentifier::VALUE    => [
                                'dataType' => DataTypeIdentifier::INTEGER,
                                'text'     => 42,
                            ],
                        ],
                        [
                            'apply' => [
                                'functionId' => FunctionIdentifier::INTEGER_ONE_AND_ONLY,
                                [
                                    AttributeIdentifier::SELECTOR => [
                                        'category'          => AttributeIdentifier::RESOURCE_CATEGORY,
                                        'dataType'          => DataTypeIdentifier::INTEGER,
                                        'contextSelectorId' => ContextSelectorIdentifier::CONTENT_SELECTOR,
                                        'mustBePresent'     => false,
                                        'path'              => '$.resource.id',
                                    ],
                                ]
                            ]
                        ],
                    ],
                ],
            ],
        ],
    ],
];
