<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Policy\Expressions\AttributeDesignator;
use Cerberus\PDP\Policy\Policy;

class AttributeDesignatorFactory extends PolicyElementFactory
{
    /**
     * @param Policy $policy
     * @param array  $data
     *
     * @return PolicyElement|AttributeDesignator
     */
    public static function create(Policy $policy, array $data): PolicyElement
    {
        return new AttributeDesignator(
            $data['category'],
            $data['dataType'],
            $data['mustBePresent'],
            $data['attributeId']
        );
    }
}
