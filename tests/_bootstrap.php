<?php
// This is global bootstrap for autoloading
$loader = require(__DIR__.'/../vendor/autoload.php');

$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
    'debug' => true,
    'includePaths' => [
        __DIR__ . '/../vendor/goaop'
    ],
]);