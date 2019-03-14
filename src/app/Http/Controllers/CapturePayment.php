<?php

namespace uisits\ipay\app\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use uisits\ipay\app\Ipay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use uisits\ipay\Exceptions\IpayExceptionHandler as ErrorHandle;

class CapturePayment extends Controller
{
    public function __construct()
    { }

    /**
     * Capture the Ipay request
     * @param Request $request
     * @return true|throw error
     */
    public function capturePayment(Request $request)
    {
        $date = Carbon::now('UTC')->format(Config('ipay.date-format'));

        //Check against DB
        $transactionCheck = Ipay::where('token', '=', $request->TOKEN)->firstOrFail();

        // //Hash the Fields
        // $postParams =  array(
        //     'action' => Config('ipay.credit-capture'),
        //     'token' => $request->TOKEN,
        //     'amount' => strval($transactionCheck->amount),
        //     'amount1' => strval($transactionCheck->amount),
        //     'numaccounts' => Config('ipay.NUM_ACCOUNTS'),
        //     'chart1' => Config('ipay.CFOAP_CHART'),
        //     'fund1' => Config('ipay.CFOAP_FUND'),
        //     'org1' => Config('ipay.CFOAP_ORG'),
        //     'account1' => Config('ipay.CFOAP_ACCOUNT'),
        //     'program1' => Config('ipay.CFOAP_PROGRAM'),
        //     'timestamp' => $date,
        //     'certification' => $this->hashCapturePaymentFields($transactionCheck->token,$this->CheckNumberFormat(strval($transactionCheck->amount)))
        // );

        // $response = \Httpful\Request::post(Config('ipay.url-test'))
        // ->body(http_build_query($postParams),\Httpful\Mime::FORM)
        // ->addHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
        // ->expectsPlain()
        // ->send();

        $client = new Client();

        $options = [
            'form_params' => [
                'action' => Config('ipay.credit-capture'),
                'token' => $request->TOKEN,
                'amount' => strval($transactionCheck->amount),
                'amount1' => strval($transactionCheck->amount),
                'numaccounts' => Config('ipay.NUM_ACCOUNTS'),
                'chart1' => Config('ipay.CFOAP_CHART'),
                'fund1' => Config('ipay.CFOAP_FUND'),
                'org1' => Config('ipay.CFOAP_ORG'),
                'account1' => Config('ipay.CFOAP_ACCOUNT'),
                'program1' => Config('ipay.CFOAP_PROGRAM'),
                'timestamp' => $date,
                'certification' => $this->hashCapturePaymentFields($transactionCheck->token, $this->CheckNumberFormat(strval($transactionCheck->amount)))
            ]
        ];

        $response = $client->post(Config('ipay.url-test'), $options);
        $response = $response->getBody()->getContents();
        $formatedResponse = $this->processResponse($response, $transactionCheck->amount);

        if ($formatedResponse["RESPONSECODE"] === '0') {
            $transactionUpdate = Ipay::where('token', '=', $request->TOKEN)->firstOrFail();

            //Update DB
            $transactionUpdate->updated_at = Carbon::now();
            $transactionUpdate->paid = 1;
            if ($transactionUpdate->save()) {
                return true;
            } else {
                throw new ErrorHandle("Failed to Save to Database", 500);
            }
        } else {
            throw new ErrorHandle($formatedResponse['RESPONSEMESSAGE'], $formatedResponse["RESPONSECODE"]);
        }
    }

    /**
     * Hash the fields to capture the payment
     * @param token
     * @param amount
     * @return hashed data of fields
     */
    public function hashCapturePaymentFields($token, $amount)
    {
        $date = Carbon::now('UTC')->format(Config('ipay.date-format'));
        $fields = $token . '|' . $amount . '|' . $date . '|' . Config('ipay.NUM_ACCOUNTS') . '|'
            . Config('ipay.CFOAP_CHART') . '|' . Config('ipay.CFOAP_FUND') . '|'
            . Config('ipay.CFOAP_ORG') . '|' . Config('ipay.CFOAP_ACCOUNT') . '|'
            . Config('ipay.CFOAP_PROGRAM') . '|' . $amount;
        return hash_hmac(Config('ipay.hash-algo'), $fields, Config('ipay.sendASCIIKey'));
    }

    /**
     * Check or Change Amount format
     * @param float $amount
     * @return float formatedNumber
     */
    private function CheckNumberFormat($dollars)
    {
        return number_format((float)$dollars, 2, '.', '');
    }

    /**
     * Process the response from Ipay Capture message
     * @param array response
     * @param float amount
     * @return array formattedResponse
     */
    private function processResponse($response, $amount)
    {
        $responseArray = explode(PHP_EOL, $response);
        return $this->formatResponse($responseArray);
    }

    /**
     * Formats the response array
     * @param array response
     * @return array response
     */
    private function formatResponse($arr)
    {
        foreach ($arr as $key => $value) {
            $multiArray[] = explode('=', $value);
        }
        array_pop($multiArray);
        foreach ($multiArray as $key => $value) {
            $response[$value[0]] = $value[1];
        }
        return $response;
    }
}
