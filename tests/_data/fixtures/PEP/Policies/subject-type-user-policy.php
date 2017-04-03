<?php

use Cerberus\Core\Decision;
use Cerberus\Core\Enums\{
    CombiningAlgorithmIdentifier, DataTypeIdentifier, FunctionIdentifier, SubjectCategoryIdentifier, SubjectIdentifier
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
                                            'matchId'             => FunctionIdentifier::STRING_EQUAL,
                                            'attributeValue'      => [
                                                'dataType' => DataTypeIdentifier::STRING,
                                                'text'     => 'user',
                                            ],
                                            'attributeDesignator' => [
                                                'category'      => SubjectCategoryIdentifier::ACCESS_SUBJECT,
                                                'attributeId'   => SubjectIdentifier::SUBJECT_TYPE,
                                                'dataType'      => DataTypeIdentifier::STRING,
                                                'mustBePresent' => true,
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
