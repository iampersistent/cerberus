<?php
declare(strict_types = 1);

namespace Picr\Cerberus;

use Illuminate\Support\ServiceProvider;

class CerberusServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->registerAuthorize();
    }

    protected function registerAuthorize()
    {
        $this->app->singleton('cerberus', function ($app) {
            return new CerberusService();
        });
    }


}