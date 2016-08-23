<?php
declare(strict_types = 1);

namespace Cerberus\PEP;

class Subject extends CategoryContainer
{
    protected $subjectIdValue;

    public function __construct(string $subjectIdValue)
    {
        $this->subjectIdValue = $subjectIdValue;
        parent::__construct($subjectIdValue);
    }

    /**
     * Returns the value of the default subjectIdValue attribute
     */
    public function getSubjectIdValue(): string
    {
        return $this->subjectIdValue;
    }
}