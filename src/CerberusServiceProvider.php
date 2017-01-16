<?php
declare(strict_types = 1);

namespace Cerberus;

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
        $this->app->singleton('cerberus', function ($app) {
            return new CerberusService(config('cerberus'));
        });
    }
}