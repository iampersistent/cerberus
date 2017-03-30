<?php
declare(strict_types = 1);

use Cerberus\Core\Enums\DataTypeIdentifier;
use Cerberus\Core\Exception\IllegalArgumentException;
use Cerberus\PDP\Policy\Expressions\AttributeValue;

class AttributeValueCest
{
    public function testNullValue(UnitTester $I)
    {
        $attributeValue = new AttributeValue(DataTypeIdentifier::STRING, null);

        $I->assertNull($attributeValue->getValue());
    }

    public function testUnsetValueException(UnitTester $I)
    {
        $exception = new IllegalArgumentException('If you need a null attribute value, it must be explicitly set');
        $I->expectException($exception, function() {
            new AttributeValue(DataTypeIdentifier::STRING);
        });
    }
}
