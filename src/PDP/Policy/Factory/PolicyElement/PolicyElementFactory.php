<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Exception\PolicyElementFactoryException;
use Cerberus\PDP\Policy\Policy;
use Exception;

abstract class PolicyElementFactory
{
    abstract public static function create(Policy $policy, array $data): PolicyElement;

    protected static function processDescription(Policy $policy, PolicyElement $element, $data)
    {
        $element->setDescription($data);
    }

    protected static function processIncomingData(Policy $policy, PolicyElement $element, $incomingData)
    {
        foreach ($incomingData as $elementName => $data) {
            $processMethod = 'process' . ucfirst($elementName);
            try {
                static::$processMethod($policy, $element, $data);
            } catch (Exception $e) {
                if (method_exists(self::class, $processMethod)) {
                    throw new PolicyElementFactoryException("There was a problem processing $elementName");
                } else {
                    throw new PolicyElementFactoryException("$elementName is not a valid element");
                }
            }
        }
    }
}
