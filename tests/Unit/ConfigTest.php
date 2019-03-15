<?php
namespace uisits\ipay\Tests\Unit;

use uisits\ipay\Tests\TestCase as TestCase;
use uisits\ipay\Exceptions\IpayExceptionHandler as ErrorHandle;
use uisits\ipay\app\Http\Controllers\IpayController;

class ConfigTest extends TestCase
{

    /**
     * @test
     */
    public function test_ipay_local_url_is_empty()
    {
        putenv("APP_ENV=testing");
        if (getenv('APP_ENV') === 'testing') {
            $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
            config(['ipay.url-test' => '']);
            Config('ipay.url-test') === '' ? abort(500, 'iPay URL not set in config') : Config('ipay.url-test');
        }
    }

    /**
     * @test
     */
    public function test_ipay_production_url_is_empty()
    {
        putenv("APP_ENV=production");
        if (getenv('APP_ENV') === 'production') {
            $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
            config(['ipay.url-production' => '']);
            Config('ipay.url-production') === '' ? abort(500, 'iPay URL not set in config') : Config('ipay.url-production');
        }
    }

    /**
     * @test
     */
    public function test_ipay_credit_action_is_empty()
    {
        try {
            config(['ipay.credit-action' => '']);
            if (Config('ipay.credit-action') === '') {
                throw new ErrorHandle('iPay Credit Action not set in config', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay Credit Action not set in config', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_credit_capture_is_empty()
    {
        try {
            config(['ipay.credit-capture' => '']);
            if (Config('ipay.credit-capture') === '') {
                throw new ErrorHandle('iPay Credit Capture not set in config', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay Credit Capture not set in config', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_siteid_is_null()
    {
        try {
            config(['ipay.siteid' => null]);
            if (Config('ipay.siteid') === null) {
                throw new ErrorHandle('iPay siteid not set in config', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay siteid not set in config', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_siteid_is_empty()
    {
        try {
            config(['ipay.siteid' => '']);
            if (Config('ipay.siteid') === '') {
                throw new ErrorHandle('iPay siteid not set in config', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay siteid not set in config', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_siteid_is_false()
    {
        try {
            config(['ipay.siteid' => false]);
            if (Config('ipay.siteid') === false) {
                throw new ErrorHandle('iPay siteid not set in config', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay siteid not set in config', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_timezone_is_empty()
    {
        try {
            config(['ipay.timezone' => '']);
            if (Config('ipay.timezone') === '') {
                throw new ErrorHandle('iPay timezone not set in config', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay timezone not set in config', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_timezone_is_not_UTC()
    {
        try {
            config(['ipay.timezone' => 'CDT']);
            if (Config('ipay.timezone') !== 'UTC') {
                throw new ErrorHandle('iPay timezone mismatch', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay timezone mismatch', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_hash_algo_is_empty()
    {
        try {
            config(['ipay.hash-algo' => '']);
            if (Config('ipay.hash-algo') === '') {
                throw new ErrorHandle('iPay hash-algo not set in config', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay hash-algo not set in config', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_hash_algo_is_not_sha1()
    {
        try {
            config(['ipay.hash-algo' => 'md5']);
            if (Config('ipay.hash-algo') !== 'sha1') {
                throw new ErrorHandle('iPay hash-algo mismatch', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay hash-algo mismatch', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_date_format_is_not_as_expected()
    {
        try {
            config(['ipay.date-format' => 'Y-m-d H:i:s']);
            if (Config('ipay.date-format') !== 'm-d-Y H:i:s') {
                throw new ErrorHandle('iPay date-format mismatch', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay date-format mismatch', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_date_format_is_empty()
    {
        try {
            config(['ipay.date-format' => '']);
            if (Config('ipay.date-format') === '') {
                throw new ErrorHandle('iPay date-format not set in config', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay date-format not set in config', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_sendASCIIKey_is_empty()
    {
        try {
            config(['ipay.sendASCIIKey' => '']);
            if (Config('ipay.sendASCIIKey') === '') {
                throw new ErrorHandle('iPay sendASCIIKey not set in config', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay sendASCIIKey not set in config', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_NUM_ACCOUNTS_is_empty()
    {
        try {
            config(['ipay.NUM_ACCOUNTS' => '']);
            if (Config('ipay.NUM_ACCOUNTS') === '') {
                throw new ErrorHandle('iPay NUM_ACCOUNTS not set in config', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay NUM_ACCOUNTS not set in config', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_CFOAP_CHART_is_empty()
    {
        try {
            config(['ipay.CFOAP_CHART' => '']);
            if (Config('ipay.CFOAP_CHART') === '') {
                throw new ErrorHandle('iPay CFOAP_CHART not set in config', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay CFOAP_CHART not set in config', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_CFOAP_FUND_is_empty()
    {
        try {
            config(['ipay.CFOAP_FUND' => '']);
            if (Config('ipay.CFOAP_FUND') === '') {
                throw new ErrorHandle('iPay CFOAP_FUND not set in config', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay CFOAP_FUND not set in config', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_CFOAP_ORG_is_empty()
    {
        try {
            config(['ipay.CFOAP_ORG' => '']);
            if (Config('ipay.CFOAP_ORG') === '') {
                throw new ErrorHandle('iPay CFOAP_ORG not set in config', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay CFOAP_ORG not set in config', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_CFOAP_ACCOUNT_is_empty()
    {
        try {
            config(['ipay.CFOAP_ACCOUNT' => '']);
            if (Config('ipay.CFOAP_ACCOUNT') === '') {
                throw new ErrorHandle('iPay CFOAP_ACCOUNT not set in config', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay CFOAP_ACCOUNT not set in config', $e->getMessage());
    }

    /**
     * @test
     */
    public function test_ipay_CFOAP_PROGRAM_is_empty()
    {
        try {
            config(['ipay.CFOAP_PROGRAM' => '']);
            if (Config('ipay.CFOAP_PROGRAM') === '') {
                throw new ErrorHandle('iPay CFOAP_PROGRAM not set in config', 500);
            }
        } catch (\Exception $e) { }
        $this->assertEquals('iPay CFOAP_PROGRAM not set in config', $e->getMessage());
    }
}
