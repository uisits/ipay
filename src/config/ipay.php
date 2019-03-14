<?php
return [

    /**
     * Define hostname. Only specific hostname allowed.
     */
    'hostname' => env('IPAY_HOSTNAME',''),

    /**
     * url to the payment site
     */
    'url-test' => env('IPAY_TEST_URL',''),
    'url-production' => env('IPAY_PROD_URL',''),

    /**
     * Default action for Credit Card Payment
     */
    'credit-action' => env('CREDIT_ACTION','registerccpayment'),

    /**
     * Default action for Credit Card Capture
     */
    'credit-capture' => env('CREDIT_CAPTURE','captureccpayment'),

    /**
     * Default SiteId
     */
    'siteid' => env('IPAY_SITEID',false),

    /**
     * Default market
     */
    'market' => 'retail',

    /**
     * Default reference id
     */
    'referenceid' => env('REFERENCEID',false),

    /**
     * Default TimeZone 'UTC'
     */
    'timezone' => 'UTC',

    /**
     * Default Hashing Algorithm
     */
    'hash-algo' => 'sha1',

    /**
     * Default Time Format
     */
    'date-format' => 'm-d-Y H:i:s',

    /**
     * Default Send ASCII KEY
     */
    'sendASCIIKey' => env('PAYMENT_SENDKEY_ASCII',false),

    /**
     * Error Configuration Code
     */
    'error-code' => env('iPayErrorCode',false),

    /**
     * Number Of Accounts
     */
    'NUM_ACCOUNTS' => env('IPAY_NUM_ACCOUNTS',false),

    /**
     * CFOAP Chart Number
     */
    'CFOAP_CHART' => env('IPAY_CFOAP_CHART1',false),

    /**
     * CFOAP FUND Number
     */
    'CFOAP_FUND' => env('IPAY_CFOAP_FUND1',false),

    /**
     * CFOAP Organization Number
     */
    'CFOAP_ORG' => env('IPAY_CFOAP_ORG1',false),

    /**
     * CFOAP Account Number
     */
    'CFOAP_ACCOUNT' => env('IPAY_CFOAP_ACCOUNT1',false),

    /**
     * CFOAP Program Number
     */
    'CFOAP_PROGRAM' => env('IPAY_CFOAP_PROGRAM1',false)

];

?>