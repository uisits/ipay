<?php

namespace uisits\ipay\Tests;
use Orchestra\Testbench\TestCase;
use uisits\ipay\IpayServiceProvider;
use uisits\ipay\app\Facades\IpayFacade;

class BeginTesting extends TestCase
{
    public $app_copy;
    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return lasselehtinen\MyPackage\IpayServiceProvider
     */
    protected function getPackageProviders($app){
        return [IpayServiceProvider::class];
    }

    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     * @return array
    */
    protected function getPackageAliases($app){
        return [
            'Ipay' => IpayFacade::class,
            'Httpful' => \Httpful\Request::class
        ];
    }

    public function setUp(){
        parent::setUp();
        $this->migrate();
    }

    public function migrate(){
        $this->loadMigrationsFrom([
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__. '/database/migrations'),
        ]);
    }

    protected function getEnvironmentSetUp($app){
        $this->app_copy = $app;
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // New Dotenv
        if (file_exists(dirname(__DIR__) . '/.env.example')) {
            (new \Dotenv\Dotenv(dirname(__DIR__), '/.env.example'))->load();
        }

        $app['config']->set('ipay.url-test', env( 'IPAY_TEST_URL'));
        $app['config']->set( 'ipay.siteid', env( 'IPAY_SITEID'));
        $app['config']->set( 'ipay.sendASCIIKey', env('PAYMENT_SENDKEY_ASCII'));
        $app['config']->set( 'ipay.NUM_ACCOUNTS', env( 'IPAY_NUM_ACCOUNTS'));
        $app['config']->set( 'ipay.CFOAP_CHART', env( 'IPAY_CFOAP_CHART1'));
        $app['config']->set( 'ipay.CFOAP_FUND', env( 'IPAY_CFOAP_FUND1'));
        $app['config']->set('ipay.CFOAP_ORG', env( 'IPAY_CFOAP_ORG1'));
        $app['config']->set( 'ipay.CFOAP_ACCOUNT', env( 'IPAY_CFOAP_ACCOUNT1'));
        $app['config']->set( 'ipay.CFOAP_PROGRAM', env( 'IPAY_CFOAP_PROGRAM1'));
    }

}
