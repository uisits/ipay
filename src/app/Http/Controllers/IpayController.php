<?php

namespace uisits\ipay\app\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use uisits\ipay\app\Http\Controllers\SendPaymentRequest;
use uisits\ipay\app\Http\Controllers\CapturePayment;
use uisits\ipay\Exceptions\IpayExceptionHandler as ErrorHandle;
use Shibalike\Config;

class ipayController
{

    /**
     * Initiate the Payment Request with Ipay server @param amount
     * @param float amount
     * @param string payment Type
     */
    public function initiatePayment($amount, $type = 'credit')
    {
        //check Config file variables
        $this->checkConfigVariables();

        //Hash the Fields
        $certificate = $this->hashSendPaymentFields($this->CheckNumberFormat($amount));

        //Send Request
        $send = new SendPaymentRequest();
        return $send->SendRequest($certificate, $this->CheckNumberFormat($amount));
    }

    /**
     * Capture the Ipay Payment
     * @param Request $request
     * @return true|false
     */
    public function capturePayment(Request $request)
    {
        $capture = new CapturePayment();
        return $capture->capturePayment($request);
    }

    /**
     * Check or Change Amount Format
     * @param float $amount
     * @return formated Number
     */
    private function CheckNumberFormat($dollars)
    {
        return number_format((float)$dollars, 2, '.', '');
    }

    /**
     * Hash the payment details using specified algorithm and hash_hmac method
     * @param $amount
     * @param hashing algorithm
     */
    private function hashSendPaymentFields($amount)
    {
        $date = Carbon::now('UTC')->format(Config('ipay.date-format'));
        $fields = $amount . '|' . Config('ipay.siteid') . '|' . $date;
        return hash_hmac(Config('ipay.hash-algo'), $fields, Config('ipay.sendASCIIKey'));
    }

    /**
     * Check validity of Config Variables
     */
    private function checkConfigVariables()
    {
        if (\App::environment('production')) {
            if (Config('ipay.url-production') === '') {
                throw new ErrorHandle("iPay URL not set in config", 500);
            }
        }
        if (Config('ipay.url-test') === '') {
            throw new ErrorHandle("iPay URL not set in config", 500);
        }
        if (Config('ipay.credit-action') === '') {
            throw new ErrorHandle("iPay Credit Action not set in config", 500);
        }
        if (Config('ipay.credit-capture') === '') {
            throw new ErrorHandle("iPay Credit Capture not set in config", 500);
        }
        if (Config('ipay.siteid') === '' || Config('ipay.siteid') === false) {
            throw new ErrorHandle("iPay siteid not set in config", 500);
        }
        if (Config('ipay.market') === '') {
            throw new ErrorHandle("iPay market not set in config", 500);
        }
        if (Config('ipay.referenceid') === '') {
            throw new ErrorHandle("iPay referenceid not set in config", 500);
        }
        if (Config('ipay.timezone') === '') {
            throw new ErrorHandle("iPay timezone not set in config", 500);
        }
        if (Config('ipay.hash-algo') === '') {
            throw new ErrorHandle("iPay hash-algo not set in config", 500);
        }
        if (Config('ipay.date-format') === '') {
            throw new ErrorHandle("iPay date-format not set in config", 500);
        }
        if (Config('ipay.sendASCIIKey') === '' || Config('ipay.sendASCIIKey') === false) {
            throw new ErrorHandle("iPay sendASCIIKey not set in config", 500);
        }
        if (Config('ipay.NUM_ACCOUNTS') === '' || Config('ipay.NUM_ACCOUNTS') === false) {
            throw new ErrorHandle("iPay NUM_ACCOUNTS not set in config", 500);
        }
        if (Config('ipay.CFOAP_CHART') === '' || Config('ipay.CFOAP_CHART') === false) {
            throw new ErrorHandle("iPay CFOAP_CHART not set in config", 500);
        }
        if (Config('ipay.CFOAP_FUND') === '' || Config('ipay.CFOAP_FUND') === false) {
            throw new ErrorHandle("iPay CFOAP_FUND not set in config", 500);
        }
        if (Config('ipay.CFOAP_ORG') === '' || Config('ipay.CFOAP_ORG') === false) {
            throw new ErrorHandle("iPay CFOAP_ORG not set in config", 500);
        }
        if (Config('ipay.CFOAP_ACCOUNT') === '' || Config('ipay.CFOAP_ACCOUNT') === false) {
            throw new ErrorHandle("iPay CFOAP_ACCOUNT not set in config", 500);
        }
        if (Config('ipay.CFOAP_PROGRAM') === '' || Config('ipay.CFOAP_PROGRAM') === false) {
            throw new ErrorHandle("iPay CFOAP_PROGRAM not set in config", 500);
        }
    }
}
