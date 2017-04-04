<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    AttributeIdentifier, CombiningAlgorithmIdentifier, ContextSelectorIdentifier, DataTypeIdentifier, FunctionIdentifier
};

$policyId = 'document-id-equals-forty-two';

return [
    'policy' => [
        'policyId'                 => "$policyId:policy",
        'ruleCombiningAlgorithmId' => CombiningAlgorithmIdentifier::DENY_OVERRIDES,
        'rules'                    => [
            [
                'ruleId' => "$policyId:rule1",
                'effect' => Decision::PERMIT,
                'target' => [
                    [
                        'anyOf' => [
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'                     => FunctionIdentifier::INTEGER_EQUAL,
                                            AttributeIdentifier::VALUE    => [
                                                'dataType' => DataTypeIdentifier::INTEGER,
                                                'text'     => 42,
                                            ],
                                            AttributeIdentifier::SELECTOR => [
                                                'category'          => AttributeIdentifier::RESOURCE_CATEGORY,
                                                'dataType'          => DataTypeIdentifier::INTEGER,
                                                'contextSelectorId' => ContextSelectorIdentifier::CONTENT_SELECTOR,
                                                'mustBePresent'     => false,
                                                'path'              => '$.resource.id',
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
