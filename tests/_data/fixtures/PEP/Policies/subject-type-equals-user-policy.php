<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    AttributeIdentifier, CombiningAlgorithmIdentifier, DataTypeIdentifier, FunctionIdentifier, SubjectIdentifier
};

$policyId = 'subject-type-equals-user';

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
                        'description' => 'subject type must equal "user',
                        'functionId'  => FunctionIdentifier::STRING_EQUAL,
                        [
                            AttributeIdentifier::VALUE => [
                                'dataType' => DataTypeIdentifier::STRING,
                                'text'     => 'user',
                            ],
                        ],
                        [
                            'apply' => [
                                'functionId' => FunctionIdentifier::STRING_ONE_AND_ONLY,
                                [
                                    AttributeIdentifier::DESIGNATOR => [
                                        'category'      => SubjectIdentifier::ACCESS_SUBJECT_CATEGORY,
                                        'attributeId'   => SubjectIdentifier::SUBJECT_TYPE,
                                        'dataType'      => DataTypeIdentifier::STRING,
                                        'mustBePresent' => true,
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
