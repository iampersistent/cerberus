<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Policy\AllOf;
use Cerberus\PDP\Policy\Policy;

class AllOfFactory extends PolicyElementFactory
{
    /**
     * @param Policy $policy
     * @param array  $data
     *
     * @return PolicyElement|AllOf
     */
    public static function create(Policy $policy, array $data): PolicyElement
    {
        $allOf = new AllOf();
        foreach ($data as $match) {
            $matchData = $match['match'];

            $match = MatchFactory::create($policy, $matchData);
            $allOf->addMatch($match);
        }

        return $allOf;
    }
}
