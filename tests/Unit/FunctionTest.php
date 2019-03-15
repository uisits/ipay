<?php
namespace uisits\ipay\Tests\Unit;

use Carbon\Carbon;
use GuzzleHttp\Client;
use uisits\ipay\app\Facades\IpayFacade;
use uisits\ipay\Tests\TestCase as TestCase;

class FunctionTest extends TestCase
{

    public function test_can_initiate_payment_request()
    {
        set_time_limit(20);
        $amount = 12.00;
        $redirect_url = IpayFacade::initiatePayment($amount);
        $this->assertContains('https://webtest.obfs.uillinois.edu/', $redirect_url);
    }

    public function test_exception_thrown_for_no_amount_specified()
    {
        set_time_limit(20);
        $this->expectException(\ArgumentCountError::class);
        $redirect_url = IpayFacade::initiatePayment();
    }

    public function test_cannot_call_function()
    {
        set_time_limit(20);
        $amount = 12.00;
        $this->expectExceptionMessage('Call to private method');
        IpayFacade::hashSendPaymentFields($amount);
    }

    public function test_receive_error_from_iPay()
    {
        set_time_limit(20);
        $this->app['config']->set('ipay.market', 'reta');
        $this->expectExceptionMessage('Market invalid');
        $amount = 12.00;
        $redirect_url = IpayFacade::initiatePayment($amount);
    }

    public function test_fake_request_to_capture_payment()
    {
        $amount = '70.50';
        $date = Carbon::now('UTC')->format(Config('ipay.date-format'));
        $fields = '892C87854DA5333F3405B8E1B0FA0897B29913C003112019' . '|' . $amount . ' |' . $date . '| ' . Config('ipay.NUM_ACCOUNTS') . '|'
            . Config('ipay.CFOAP_CHART') . '|' . Config('ipay.CFOAP_FUND') . '|'
            . Config('ipay.CFOAP_ORG') . '|' . Config('ipay.CFOAP_ACCOUNT') . '|'
            . Config('ipay.CFOAP_PROGRAM') . '|' . $amount;

        $hashed = hash_hmac(Config('ipay.hash-algo'), $fields, Config('ipay.sendASCIIKey'));


        $client = new Client();

        $options = [
            'form_params' => [
                'action'             => Config('ipay.credit-action'),
                'siteid'             => Config('ipay.siteid'),
                'amount'             => '',
                'market'             => Config('ipay.market'),
                'timestamp'          => $date,
                'certification'      => $hashed
            ]
        ];

        $response = $client->post(Config('ipay.url-test'), $options);
        $this->assertRegexp('/Request certificate does not match calculated certificate/', $response->getBody()->getContents());
    }
}
