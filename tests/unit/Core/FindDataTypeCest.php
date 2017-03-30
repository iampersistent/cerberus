<?php
declare(strict_types = 1);

use Cerberus\Core\Enums\DataTypeIdentifier;
use Cerberus\Core\Exception\DataTypeException;
use Cerberus\Core\FindDataType;

class FindDataTypeCest
{
    public function testArrayType(UnitTester $I)
    {
        $exception = new DataTypeException('There is no defined array data type');
        $I->expectException($exception, function() {
            FindDataType::handle([]);
        });
    }

    public function testBoolean(UnitTester $I)
    {
        $I->assertSame(DataTypeIdentifier::BOOLEAN, FindDataType::handle(false));
    }

    public function testDate(UnitTester $I)
    {
        $I->assertSame(DataTypeIdentifier::DATETIME, FindDataType::handle(new DateTime()));
    }

    public function testDouble(UnitTester $I)
    {
        $I->assertSame(DataTypeIdentifier::DOUBLE, FindDataType::handle(3.14));
    }

    public function testInteger(UnitTester $I)
    {
        $I->assertSame(DataTypeIdentifier::INTEGER, FindDataType::handle(42));
    }

    public function testString(UnitTester $I)
    {
        $I->assertSame(DataTypeIdentifier::STRING, FindDataType::handle('Data'));
    }

    public function testInvalidType(UnitTester $I)
    {
        $exception = new DataTypeException('stdClass is not a valid data type');
        $I->expectException($exception, function() {
            FindDataType::handle(new stdClass());
        });
    }
}
