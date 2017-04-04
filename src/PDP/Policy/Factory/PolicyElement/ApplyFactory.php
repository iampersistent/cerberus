<?php
declare(strict_types = 1);

namespace Cerberus\PDP\Policy\Factory\PolicyElement;

use Exception;
use Cerberus\PDP\Contract\PolicyElement;
use Cerberus\PDP\Exception\PolicyElementFactoryException;
use Cerberus\PDP\Policy\Expressions\Apply;
use Cerberus\PDP\Policy\Policy;

class ApplyFactory extends ExpressionParentFactory
{
    /**
     * @param Policy $policy
     * @param array  $data
     *
     * @return PolicyElement|Apply
     */
//    public static function create(Policy $policy, array $data): PolicyElement
//    {
//        $description = $data['description'] ?? '';
//        $apply = new Apply($data['functionId'], $description);
//        if (isset($data['apply'])) {
//            foreach ($data['apply'] as $applyData) {
//                $applyArgument = ApplyFactory::create($policy, $applyData);
//                $apply->addArgument($applyArgument);
//            }
//        }
//        if (isset($data[AttributeIdentifier::DESIGNATOR])) {
//            $attributeDesignator = AttributeDesignatorFactory::create($policy, $data[AttributeIdentifier::DESIGNATOR]);
//            $apply->addArgument($attributeDesignator);
//        }
//        if (isset($data[AttributeIdentifier::SELECTOR])) {
//            $attributeDesignator = AttributeSelectorFactory::create($policy, $data[AttributeIdentifier::SELECTOR]);
//            $apply->addArgument($attributeDesignator);
//        }
//
//        return $apply;
//    }
    public static function create(Policy $policy, array $data): PolicyElement
    {
        $apply = new Apply($data['functionId']);
        $apply->setDescription($data['description'] ?? '');
        unset($data['functionId'], $data['description']);
        self::processArgumentsData($policy, $apply, $data);

        return $apply;
    }

    protected static function processArgumentsData(Policy $policy, PolicyElement $element, $argumentsData)
    {
        foreach ($argumentsData as $data) {
            $elementName = key($data);
            $processMethod = 'process' . ucfirst($elementName);
            try {
                static::$processMethod($policy, $element, $data[$elementName]);
            } catch (Exception $e) {
                if (method_exists(self::class, $processMethod)) {
                    throw new PolicyElementFactoryException("There was a problem processing $elementName");
                } else {
                    throw new PolicyElementFactoryException("$elementName is not a valid element");
                }
            }
        }
    }

    protected static function processApply(Policy $policy, PolicyElement $element, $data)
    {
        $apply = ApplyFactory::create($policy, $data);
        $element->addArgument($apply);
    }

    protected static function processFunction(Policy $policy, PolicyElement $element, $data)
    {
        $attributeDesignator = FunctionExpressionFactory::create($policy, $data);
        $element->addArgument($attributeDesignator);
    }

    protected static function processAttributeDesignator(Policy $policy, PolicyElement $element, $data)
    {
        $attributeDesignator = AttributeDesignatorFactory::create($policy, $data);
        $element->addArgument($attributeDesignator);
    }

    protected static function processAttributeSelector(Policy $policy, PolicyElement $element, $data)
    {
        $attributeDesignator = AttributeSelectorFactory::create($policy, $data);
        $element->addArgument($attributeDesignator);
    }

    protected static function processAttributeValue(Policy $policy, PolicyElement $element, $data)
    {
        $attributeDesignator = AttributeValueFactory::create($policy, $data);
        $element->addArgument($attributeDesignator);
    }

    protected static function processVariableReference(Policy $policy, PolicyElement $element, $data)
    {
        $attributeDesignator = VariableReferenceFactory::create($policy, $data);
        $element->addArgument($attributeDesignator);
    }
}
