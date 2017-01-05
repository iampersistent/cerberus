<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Policy\AnyOf;
use Cerberus\PDP\Policy\Policy;

class AnyOfFactory extends PolicyElementFactory
{
    /**
     * @param Policy $policy
     * @param array  $data
     *
     * @return PolicyElement|AnyOf
     */
    public static function create(Policy $policy, array $data): PolicyElement
    {
        $anyOf = new AnyOf();
        foreach ($data as $allOfData) {
            $allOf = AllOfFactory::create($policy, $allOfData['allOf']);
            $anyOf->addAllOf($allOf);
        }

        return $anyOf;
    }
}
