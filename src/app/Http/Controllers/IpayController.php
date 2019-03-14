<?php

namespace uisits\ipay\app\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use uisits\ipay\app\Http\Controllers\SendPaymentRequest;
use uisits\ipay\app\Http\Controllers\CapturePayment;

class ipayController
{

    /**
     * Initiate the Payment Request with Ipay server @param amount
     * @param float amount
     * @param string payment Type
     */
    public function initiatePayment($amount,$type='credit'){
        //Hash the Fields
        $certificate = $this->hashSendPaymentFields($this->CheckNumberFormat($amount));

        //Send Request
        $send = new SendPaymentRequest();
        return $send->SendRequest($certificate,$this->CheckNumberFormat($amount));
    }

    /**
     * Capture the Ipay Payment
     * @param Request $request
     * @return true|false
     */
    public function capturePayment(Request $request){
        $capture = new CapturePayment();
        return $capture->capturePayment($request);
    }

    /**
     * Check or Change Amount Format
     * @param float $amount
     * @return formated Number
     */
    private function CheckNumberFormat($dollars){
        return number_format((float)$dollars, 2, '.', '');
    }

    /**
     * Hash the payment details using specified algorithm and hash_hmac method
     * @param $amount
     * @param hashing algorithm
     */
    private function hashSendPaymentFields($amount){
        $date = Carbon::now('UTC')->format(Config('ipay.date-format'));
        $fields = $amount.'|'.Config('ipay.siteid').'|'.$date;
        return hash_hmac(Config('ipay.hash-algo'),$fields,Config('ipay.sendASCIIKey'));
    }
}

?>