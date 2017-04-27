<?php
declare(strict_types = 1);

namespace TestData;

class ChildObject
{
    protected $id;
    protected $parent;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return (string)$this->id;
    }

    /** @return ParentObject */
    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(ParentObject $parent)
    {
        $this->parent = $parent;

        return $this;
    }
}