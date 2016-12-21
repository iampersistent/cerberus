<?php
declare(strict_types = 1);

use Cerberus\Core\{
    FindDataType, Identifier
};

class FindDataTypeCest
{
    public function testBoolean(UnitTester $I)
    {
        $I->assertSame(Identifier::DATATYPE_BOOLEAN, FindDataType::handle(false));
    }

    public function testDate(UnitTester $I)
    {
        $I->assertSame(Identifier::DATATYPE_DATETIME, FindDataType::handle(new DateTime()));
    }

    public function testDouble(UnitTester $I)
    {
        $I->assertSame(Identifier::DATATYPE_DOUBLE, FindDataType::handle(3.14));
    }

    public function testInteger(UnitTester $I)
    {
        $I->assertSame(Identifier::DATATYPE_INTEGER, FindDataType::handle(42));
    }

    public function testString(UnitTester $I)
    {
        $I->assertSame(Identifier::DATATYPE_STRING, FindDataType::handle('Data'));
    }
}