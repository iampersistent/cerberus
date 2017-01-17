<?php
declare(strict_types = 1);

namespace Cerberus;

use Cerberus\PDP\Utility\ArrayProperties;
use Illuminate\Support\ServiceProvider;

class CerberusServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/cerberus.php' => config_path('cerberus.php'),
        ]);
    }

    public function register()
    {
        $this->registerAuthorize();
    }

    protected function registerAuthorize()
    {
        $this->app->singleton(CerberusService::class, function ($app) {
            return new CerberusService(new ArrayProperties(config('cerberus')));
        });
    }
}