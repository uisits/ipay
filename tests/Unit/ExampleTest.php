<?php
namespace uisits\ipay\Tests\Unit;

use uisits\ipay\Tests\BeginTesting as TestCase;
use uisits\ipay\app\Facades\IpayFacade;
use Carbon\Carbon;

class ExampleTest extends TestCase{

    public function test_can_initiate_payment_request(){
        set_time_limit(20);
        $amount = 12.00;
        $redirect_url = IpayFacade::initiatePayment($amount);
        $this->assertContains( 'https://webtest.obfs.uillinois.edu/',$redirect_url);
    }

    public function test_exception_thrown_for_no_amount_specified(){
        set_time_limit(20);
        $this->expectException(\ArgumentCountError::class);
        $redirect_url = IpayFacade::initiatePayment();
    }

    public function test_cannot_call_function(){
        set_time_limit(20);
        $amount = 12.00;
        $this->expectExceptionMessage('Call to private method');
        IpayFacade:: hashSendPaymentFields($amount);
    }

    public function test_receive_error_from_iPay(){
        set_time_limit(20);
        $this->app['config']->set('ipay.market','reta');
        $this->expectExceptionMessage('Market invalid');
        $amount = 12.00;
        $redirect_url = IpayFacade::initiatePayment($amount);
        
    }

    public function test_fake_request_to_capture_payment(){
        $date = Carbon::now('UTC')->format(Config('ipay.date-format'));
        $fields = '892C87854DA5333F3405B8E1B0FA0897B29913C003112019'.'|'.'12.78'. ' |'.$date .'| ' .Config('ipay.NUM_ACCOUNTS').'|'
              .Config('ipay.CFOAP_CHART').'|'.Config('ipay.CFOAP_FUND').'|'
              .Config('ipay.CFOAP_ORG').'|'.Config('ipay.CFOAP_ACCOUNT').'|'
              .Config('ipay.CFOAP_PROGRAM').'|'.'12.78';

        $hashed = hash_hmac(Config('ipay.hash-algo'),$fields,Config('ipay.sendASCIIKey'));

        $postParams =  array(
            'action' => Config('ipay.credit-capture'),
            'token' => '8148A9E9AFA4C54F67EB3C4CE55E1D571DD6ED9102122019',
            'amount' => strval('12.78'),
            'amount1' => strval( '12.78'),
            'numaccounts' => Config('ipay.NUM_ACCOUNTS'),
            'chart1' => Config('ipay.CFOAP_CHART'),
            'fund1' => Config('ipay.CFOAP_FUND'),
            'org1' => Config('ipay.CFOAP_ORG'),
            'account1' => Config('ipay.CFOAP_ACCOUNT'),
            'program1' => Config('ipay.CFOAP_PROGRAM'),
            'timestamp' => $date,
            'certification' => $hashed
        );
        set_time_limit(20);
        $response = \Httpful\Request::post(Config('ipay.url-test'))
            ->body(http_build_query($postParams),\Httpful\Mime::FORM)
            ->addHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
            ->expectsPlain()
        ->send();

        $this->assertRegexp('/Request certificate does not match calculated certificate/', $response->body);
    }

}