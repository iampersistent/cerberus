<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory;

use Cerberus\PDP\Policy\Factory\PolicyElement\AnyOfFactory;
use Cerberus\PDP\Policy\Target;

class TargetFactory
{
    public static function create($policy, $data)
    {
        $anyOf = AnyOfFactory::create($policy, $data);
        $target = new Target();
        $target->addAnyOf($anyOf);

        return $target;
    }
}
