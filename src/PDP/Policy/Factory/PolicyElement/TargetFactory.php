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
        self::processIncomingData($policy, $target, $data);

        return $target;
    }

    protected static function processAnyOf(Policy $policy, Target $target, $data)
    {
        foreach ($data as $anyOfData) {
            $anyOf = AnyOfFactory::create($policy, $anyOfData);
            $target->addAnyOf($anyOf);
        }
    }
}
