<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    CombiningAlgorithmIdentifier, DataTypeIdentifier, FunctionIdentifier
};

return [
    'policy' => [
        'ruleCombiningAlgorithmId' => CombiningAlgorithmIdentifier::DENY_OVERRIDES,
        'policyId'                 => 'dynamic:policy',
        'variableDefinition'       => [
            'variableId' => 'resourceMatch',
            'apply'      => [
                'functionId' => FunctionIdentifier::STRING_EQUAL,
                'apply'      => [
                    [
                        'functionId'                               => FunctionIdentifier::STRING_ONE_AND_ONLY,
                        'attributeDesignator or attributeSelector' => [
                            'category'      => '',
                            'dataType'      => DataTypeIdentifier::STRING,
                            'mustBePresent' => false,
                        ],
                    ],
                    [
                        'functionId'                               => FunctionIdentifier::STRING_ONE_AND_ONLY,
                        'attributeDesignator or attributeSelector' => [
                            'category'      => '',
                            'dataType'      => DataTypeIdentifier::STRING,
                            'mustBePresent' => false,
                        ],
                    ],
                ],
            ],
        ],
        'rules'                    => [
            [
                'ruleId'      => 'dynamic:read',
                'effect'      => Decision::PERMIT,
                'description' => 'Subject can read resource',
                'condition'   => [
                    'variableReference' => [
                        'variableId' => 'resourceMatch',
                    ],
                ],
            ],
        ],
    ],
];