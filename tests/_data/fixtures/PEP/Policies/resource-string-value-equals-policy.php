<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    AttributeIdentifier, CombiningAlgorithmIdentifier, DataTypeIdentifier, FunctionIdentifier
};

$policyName = 'resource-string-value-equals-policy';
$ruleName = 'stringPropertyMatchRule';

return [
    'policy' => [
        'policyId'                 => "$policyName:policy",
        'ruleCombiningAlgorithmId' => CombiningAlgorithmIdentifier::DENY_OVERRIDES,
        'rules'                    => [
            [
                'ruleId'      => "$policyName:$ruleName",
                'effect'      => Decision::PERMIT,
                'description' => 'Resource can be accessed if string matches',
                'target'      => [
                    [
                        'anyOf' => [
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'                       => FunctionIdentifier::STRING_EQUAL,
                                            AttributeIdentifier::VALUE      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'John Smith',
                                            ],
                                            AttributeIdentifier::DESIGNATOR => [
                                                'category'      => AttributeIdentifier::RESOURCE_CATEGORY,
                                                'attributeId'   => 'document:client-name',
                                                'dataType'      => DataTypeIdentifier::STRING,
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
