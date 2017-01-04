<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Policy\Match;
use Cerberus\PDP\Policy\Policy;

class MatchFactory extends PolicyElementFactory
{
    /**
     * @param Policy $policy
     * @param array  $data
     *
     * @return PolicyElement|Match
     */
    public static function create(Policy $policy, array $data): PolicyElement
    {
        $match = new Match($data['matchId']);
        self::processIncomingData($policy, $match, $data);

        return $match;
    }

    protected static function processAttributeDesignator(Policy $policy, Match $match, $data)
    {
        $attributeDesignator = AttributeDesignatorFactory::create($policy, $data);
        $match->setAttributeBase($attributeDesignator);
    }

    protected static function processAttributeSelector(Policy $policy, Match $match, $data)
    {
        $attributeSelector = AttributeSelectorFactory::create($policy, $data);
        $match->setAttributeBase($attributeSelector);
    }

    protected static function processAttributeValue(Policy $policy, Match $match, $data)
    {
        $attributeValue = AttributeValueFactory::create($policy, $data);
        $match->setAttributeValue($attributeValue);
    }
}
