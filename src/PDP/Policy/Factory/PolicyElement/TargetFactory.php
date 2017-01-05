<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Policy\Policy;
use Cerberus\PDP\Policy\Target;

class TargetFactory extends PolicyElementFactory
{
    /**
     * @param Policy $policy
     * @param array  $data
     *
     * @return Target|PolicyElement
     */
    public static function create(Policy $policy, array $data): PolicyElement
    {
        $target = new Target();
        foreach ($data as $anyOfData) {
            $anyOf = AnyOfFactory::create($policy, $anyOfData['anyOf']);
            $target->addAnyOf($anyOf);
        }

        return $target;
    }
}
