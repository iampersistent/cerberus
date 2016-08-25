<?php
declare(strict_types = 1);

use Cerberus\Core\{CategoryType, FindDataType};

class FindDataTypeCest
{
    public function testArray(UnitTester $I)
    {
        $I->assertSame(CategoryType::ID_DATATYPE_ARRAY, FindDataType::handle(['data']));
    }

    public function testBoolean(UnitTester $I)
    {
        $I->assertSame(CategoryType::ID_DATATYPE_BOOLEAN, FindDataType::handle(false));
    }

    public function testDate(UnitTester $I)
    {
        $I->assertSame(CategoryType::ID_DATATYPE_DATETIME, FindDataType::handle(new DateTime()));
    }

    public function testDouble(UnitTester $I)
    {
        $I->assertSame(CategoryType::ID_DATATYPE_DOUBLE, FindDataType::handle(3.14));
    }

    public function testInteger(UnitTester $I)
    {
        $I->assertSame(CategoryType::ID_DATATYPE_INTEGER, FindDataType::handle(42));
    }

    public function testString(UnitTester $I)
    {
        $I->assertSame(CategoryType::ID_DATATYPE_STRING, FindDataType::handle('Data'));
    }
}