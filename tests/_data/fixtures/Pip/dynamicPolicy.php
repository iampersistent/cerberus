<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\DataTypeIdentifier;
use Cerberus\PDP\Combiner\CombiningAlgorithm;
use Cerberus\PDP\Policy\FunctionDefinition;

return [
    'policy' => [
        'ruleCombiningAlgorithmId' => CombiningAlgorithm::DENY_OVERRIDES,
        'policyId'                 => 'dynamic:policy',
        'variableDefinition'       => [
            'variableId' => 'resourceMatch',
            'apply'      => [
                'functionId' => FunctionDefinition::STRING_EQUAL,
                'apply'      => [
                    [
                        'functionId'                               => FunctionDefinition::STRING_ONE_AND_ONLY,
                        'attributeDesignator or attributeSelector' => [
                            'category'      => '',
                            'dataType'      => DataTypeIdentifier::STRING,
                            'mustBePresent' => false,
                        ],
                    ],
                    [
                        'functionId'                               => FunctionDefinition::STRING_ONE_AND_ONLY,
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