<?php
declare(strict_types = 1);


class DynamicPermissionsCest
{
    public function testAddOwnerPermission(UnitTester $I)
    {
        $fileData = [
            'fileId' => '123',
            'userId' => '321',
            'role' => 'NA',
            'access' => 'write'
        ];
        /**
         * access types
         * READ
         * WRITE
         * OWN
         */



    }
}