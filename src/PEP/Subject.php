<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

use Cerberus\Core\Identifier;

class Subject extends CategoryContainer
{
    protected $subjectIdValue;

    public function __construct(string $subjectIdValue)
    {
        $this->subjectIdValue = $subjectIdValue;
        parent::__construct(Identifier::SUBJECT_CATEGORY_ACCESS_SUBJECT);
        $this->addAttribute('subject:subject-id', $subjectIdValue);
    }

    /**
     * Returns the value of the default subjectIdValue attribute
     */
    public function getSubjectIdValue(): string
    {
        return $this->subjectIdValue;
    }
}