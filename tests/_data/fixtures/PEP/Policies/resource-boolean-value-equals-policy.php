<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    AttributeIdentifier, CombiningAlgorithmIdentifier, DataTypeIdentifier, FunctionIdentifier
};

$policyName = 'resource-boolean-value-equals-policy';
$ruleName = 'booleanPropertyMatchRule';

return [
    'policy' => [
        'policyId'                 => "$policyName:policy",
        'ruleCombiningAlgorithmId' => CombiningAlgorithmIdentifier::DENY_OVERRIDES,
        'rules'                    => [
            [
                'ruleId'      => "$policyName:$ruleName",
                'effect'      => Decision::PERMIT,
                'description' => 'Resource can be accessed if boolean match is true',
                'target'      => [
                    [
                        'anyOf' => [
                            [
                                'allOf' => [
                                    [
                                        'match' => [
                                            'matchId'                       => FunctionIdentifier::BOOLEAN_EQUAL,
                                            AttributeIdentifier::VALUE      => [
                                                'dataType' => DataTypeIdentifier::BOOLEAN,
                                                'text'     => true,
                                            ],
                                            AttributeIdentifier::DESIGNATOR => [
                                                'category'      => AttributeIdentifier::RESOURCE_CATEGORY,
                                                'attributeId'   => 'document:is-public',
                                                'dataType'      => DataTypeIdentifier::BOOLEAN,
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
