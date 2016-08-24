<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

class SubjectMapper extends CategoryContainerMapper
{
    protected $className = Subject::class;

    protected function resolveAttributeId(string $attributeId): string
    {
//        if ($attributeId.equals(Subject.SUBJECT_ID_KEY)) {
//            return getPepConfig().getDefaultSubjectId();
//        }
        return $attributeId;
    }
}