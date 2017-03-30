<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Enums\SubjectCategoryIdentifier;

class Subject extends CategoryContainer
{
    protected $subjectIdValue;

    public function __construct(string $subjectIdValue, string $subjectType = 'user')
    {
        $this->subjectIdValue = $subjectIdValue;
        parent::__construct(SubjectCategoryIdentifier::ACCESS_SUBJECT);
        $this->addAttribute('subject:subject-id', $subjectIdValue);
        $this->addAttribute('subject:subject-type', $subjectType);
    }

    /**
     * Returns the value of the default subjectIdValue attribute
     */
    public function getSubjectIdValue(): string
    {
        return $this->subjectIdValue;
    }
}