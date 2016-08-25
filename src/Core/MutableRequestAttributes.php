<?php
declare(strict_types = 1);

namespace Cerberus\Core;

class MutableRequestAttributes extends MutableAttributeCategory
{
    protected $id;

    public function __construct($id, $categoryIdentifier)
    {
        $this->id = $id;
        parent::__construct($categoryIdentifier);
    }


}