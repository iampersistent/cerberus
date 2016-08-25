<?php
// This is global bootstrap for autoloading
$loader = require(__DIR__.'/../vendor/autoload.php');
$loader->addPsr4('Test\\', __DIR__ . '/_data/fixtures');

$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
    'debug' => true,
    'includePaths' => [
        __DIR__ . '/../vendor/goaop',
    ],
]);