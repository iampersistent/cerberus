<?php
declare(strict_types = 1);

namespace Cerberus\Core;

class RequestAttributes extends AttributeCategory
{
    protected $id;

    public function __construct($id, $categoryIdentifier)
    {
        $this->id = $id;
        parent::__construct($categoryIdentifier);
    }

    public function getId()
    {
        return $this->id;
    }
}