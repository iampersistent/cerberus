<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Enums\SubjectIdentifier;

class Subject extends CategoryContainer
{
    protected $subjectIdValue;

    public function __construct(string $subjectIdValue, string $subjectType = 'user')
    {
        $this->subjectIdValue = $subjectIdValue;
        parent::__construct(SubjectIdentifier::ACCESS_SUBJECT_CATEGORY);
        $this->addAttribute(SubjectIdentifier::SUBJECT_ID, $subjectIdValue);
        $this->addAttribute(SubjectIdentifier::SUBJECT_TYPE, $subjectType);
    }

    /**
     * Returns the value of the default subjectIdValue attribute
     */
    public function getSubjectIdValue(): string
    {
        return $this->subjectIdValue;
    }
}