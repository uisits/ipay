<?php
namespace uisits\ipay\Exceptions;

use uisits\ipay\Exceptions\HandlerIpay;
use Exception;

class IpayExceptionHandler extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {

    }
}