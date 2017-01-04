<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory;

use Cerberus\PDP\Exception\CombinerException;

class CombinerFactory
{
    public static function create($data)
    {
        $parts = explode(':', $data);
        $identifier = array_pop($parts);
        $combinerClass = '\\Cerberus\\PDP\\Combiner\\'.str_replace('-', '', ucwords($identifier, '-'));
        if (! class_exists($combinerClass)) {
            $message = "Either combiner $identifier needs to be implemented or it is invalid";

            throw new CombinerException($message);
        }

        return new $combinerClass($identifier);
    }
}
