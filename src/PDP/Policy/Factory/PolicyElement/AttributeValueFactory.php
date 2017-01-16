<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Policy\Expressions\AttributeValue;
use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Policy\Policy;

class AttributeValueFactory extends PolicyElementFactory
{
    /**
     * @param Policy $policy
     * @param array  $data
     *
     * @return PolicyElement|AttributeValue
     */
    public static function create(Policy $policy, array $data): PolicyElement
    {
        return new AttributeValue($data['dataType'], $data['text']);
    }
}
