<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    AttributeIdentifier, CombiningAlgorithmIdentifier, DataTypeIdentifier, FunctionIdentifier
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
                    'apply' => [
                        'description' => 'make sure all of the checks evaluate to true',
                        'functionId' => FunctionIdentifier::STRING_IS_IN,
                        [
                            'apply' => [
                                'functionId' => FunctionIdentifier::STRING_ONE_AND_ONLY,
                                [
                                    AttributeIdentifier::SELECTOR => [
                                        'category'          => AttributeIdentifier::RESOURCE_CATEGORY,
                                        'contextSelectorId' => 'childObject',
                                        'dataType'          => DataTypeIdentifier::STRING,
                                        'mustBePresent'     => false,
                                        'path'              => '$.resource.paths.publicId',
                                    ],
                                ],
                            ],
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
];
