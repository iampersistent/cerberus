<?php
declare(strict_types = 1);

use Cerberus\PDP\Policy\Expressions\AttributeValue;
use Cerberus\Core\Exception\IllegalArgumentException;
use Cerberus\Core\Identifier;

class AttributeValueCest
{
    public function testNullValue(UnitTester $I)
    {
        $attributeValue = new AttributeValue(Identifier::DATATYPE_STRING, null);

        $I->assertNull($attributeValue->getValue());
    }

    public function testUnsetValueException(UnitTester $I)
    {
        $exception = new IllegalArgumentException('If you need a null attribute value, it must be explicitly set');
        $I->expectException($exception, function() {
            new AttributeValue(Identifier::DATATYPE_STRING);
        });
    }
}
