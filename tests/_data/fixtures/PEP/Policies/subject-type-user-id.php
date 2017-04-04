<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    AttributeIdentifier, CombiningAlgorithmIdentifier, DataTypeIdentifier, FunctionIdentifier
};

$policyId = 'subject-type-user';

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
                                            'matchId'                     => FunctionIdentifier::STRING_EQUAL,
                                            AttributeIdentifier::VALUE    => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'user',
                                            ],
                                            AttributeIdentifier::SELECTOR => [
                                                'category'          => AttributeIdentifier::RESOURCE_CATEGORY,
                                                'dataType'          => DataTypeIdentifier::STRING,
                                                'contextSelectorId' => 'file',
                                                'mustBePresent'     => false,
                                                'path'              => '$.resource.galleryIds',
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
