<?php

namespace uisits\ipay;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use uisits\ipay\app\Http\Controllers\IpayController;
use uisits\ipay\Exceptions\HandlerIpay;

class IpayServiceProvider extends ServiceProvider
{

    public function boot()
    {

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->publishes([
            __DIR__ . '/config/ipay.php' => config_path('ipay.php'),
            __DIR__ . '/resources/views/' => base_path('/resources/views/vendor/ipay/'),
            __DIR__ . '/database/migrations' => base_path('/database/migrations/'),
            __DIR__ . '/app/Ipay.php' => base_path('/app/Ipay.php')
        ], 'ipay-config');

        $this->loadViewsFrom(__DIR__ . '/resources/views/', 'courier');
    }

    public function register()
    {

        $this->mergeConfigFrom(
            __DIR__ . '/config/ipay.php',
            'ipay'
        );

        \App::singleton(\Illuminate\Contracts\Debug\ExceptionHandler::class, HandlerIpay::class);

        $this->app->bind('ipay-facade', function () {
            return new IpayController;
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('Client', '\GuzzleHttp\Client');
    }
}
