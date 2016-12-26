<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy;

use Flow\JSONPath\JSONPath;

class Content
{
    public function __construct($data)
    {
        $data = [];
        $this->pathData = new JSONPath($data);
    }

    public function evaluate($path)
    {
        $data = $this->pathData->find($path)->data();
        if (count($data) === 1) {
            return $data[0];
        }

        return $data;
    }

    public function find($path)
    {
        return $this->pathData->find($path);
    }
}