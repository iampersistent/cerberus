<?php
declare(strict_types = 1);

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

    public function testInvalidType(UnitTester $I)
    {
        $exception = new DataTypeException('stdClass is not a valid data type');
        $I->expectException($exception, function() {
            FindDataType::handle(new stdClass());
        });
    }
}
