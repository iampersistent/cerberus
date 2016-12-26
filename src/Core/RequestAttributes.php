<?php
declare(strict_types = 1);

namespace Cerberus\Core;

use Ds\Map;

class RequestAttributes extends AttributeCategory
{
    protected $contentRoot;
    protected $id;

    public function __construct($id, $categoryIdentifier)
    {
        $this->id = $id;
        $this->contentRoot = new Map();
        parent::__construct($categoryIdentifier);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getContentRoot(): Map
    {
        return $this->contentRoot;
    }

    public function addContent($identifier, $content): self
    {
        $this->contentRoot->put($identifier, $content);

        return $this;
    }
}