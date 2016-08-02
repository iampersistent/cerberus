<?php
declare(strict_types = 1);

namespace Picr\Cerberus\Facades;

use Illuminate\Support\Facades\Facade;

class CerberusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cerberus';
    }
}