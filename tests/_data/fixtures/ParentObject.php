<?php
declare(strict_types = 1);

namespace TestData;

class ParentObject
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return (string)$this->id;
    }
}