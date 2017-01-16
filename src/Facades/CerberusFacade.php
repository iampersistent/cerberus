<?php
declare(strict_types = 1);

namespace Cerberus\Facades;

use Cerberus\CerberusService;
use Illuminate\Support\Facades\Facade;

class CerberusFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CerberusService::class;
    }
}
