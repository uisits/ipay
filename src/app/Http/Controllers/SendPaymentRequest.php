<?php

namespace uisits\ipay\app\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use uisits\ipay\app\Ipay;
use uisits\ipay\Exceptions\IpayExceptionHandler as ErrorHandle;

class SendPaymentRequest
{

    public function __construct()
    { }

    /**
     * Send Payment Request to Ipay Server
     * @param @certificate
     * @param $amount
     * @return Http URL to Ipay Server
     */
    public function SendRequest($hashCertificate, $amount)
    {

        $date = Carbon::now('UTC')->format(Config('ipay.date-format'));

        // $response = httpful\Request::post(Config('ipay.url-test'))
        //     ->addHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
        //     ->body(['action'             => Config('ipay.credit-action'),
        //             'siteid'             => Config('ipay.siteid'),
        //             'amount'             => $amount,
        //             'market'             => Config('ipay.market'),
        //             'timestamp'          => $date,
        //             'certification'      => $hashCertificate],
        //         Httpful\Mime::FORM)
        //     ->expectsPlain()->send();

        $client = new Client();

        $options = [
            'form_params' => [
                'action'             => Config('ipay.credit-action'),
                'siteid'             => Config('ipay.siteid'),
                'amount'             => $amount,
                'market'             => Config('ipay.market'),
                'timestamp'          => $date,
                'certification'      => $hashCertificate
            ]
        ];

        $response = $client->post(Config('ipay.url-test'),$options);
        $response = $response->getBody()->getContents();
        return $this->processResponse($response, $amount);
    }

    /**
     * process the response
     * @param HTTP Response $response
     * @param float $amount
     * @return HTTP url to Ipay Server |throw error
     */
    private function processResponse($response, $amount)
    {
        $responseArray = explode(PHP_EOL, $response);
        $formatedResponse = $this->formatResponse($responseArray);

        if ($formatedResponse["RESPONSECODE"] === '0') {
            $transaction = new Ipay();
            $transaction->transactionid = $formatedResponse["TRANSACTIONID"];
            $transaction->token = $formatedResponse["TOKEN"];
            $transaction->certification = $formatedResponse["CERTIFICATION"];
            $transaction->amount = $amount;
            $transaction->paid = 0;
            $transaction->created_at = Carbon::now();
            if ($transaction->save()) {
                return $formatedResponse['REDIRECT'] . '?TOKEN=' . $formatedResponse['TOKEN'];
            } else {
                throw new ErrorHandle("Failed to Save Transaction!", 500);
            }
        } else {
            throw new ErrorHandle($formatedResponse['RESPONSEMESSAGE'], $formatedResponse["RESPONSECODE"]);
        }
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
