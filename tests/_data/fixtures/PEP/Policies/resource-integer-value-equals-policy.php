<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    AttributeIdentifier, CombiningAlgorithmIdentifier, DataTypeIdentifier, FunctionIdentifier
};

$policyName = 'resource-integer-value-equals-policy';
$ruleName = 'integerPropertyMatchRule';

return [
    'policy' => [
        'policyId'                 => "$policyName:policy",
        'ruleCombiningAlgorithmId' => CombiningAlgorithmIdentifier::DENY_OVERRIDES,
        'rules'                    => [
            [
                'ruleId'      => "$policyName:$ruleName",
                'effect'      => Decision::PERMIT,
                'description' => 'Resource can be accessed if integer matches',
                'target'      => [
                    [
                        'anyOf' => [
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'                       => FunctionIdentifier::INTEGER_EQUAL,
                                            AttributeIdentifier::VALUE      => [
                                                'dataType' => DataTypeIdentifier::INTEGER,
                                                'text'     => 123456,
                                            ],
                                            AttributeIdentifier::DESIGNATOR => [
                                                'category'      => AttributeIdentifier::RESOURCE_CATEGORY,
                                                'attributeId'   => 'document:document-size',
                                                'dataType'      => DataTypeIdentifier::INTEGER,
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
