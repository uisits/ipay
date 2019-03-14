<?php

namespace uisits\ipay\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class HandlerIpay extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
	    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException)
        {
		    abort(404, 'Page Not Found');
        }
	    if($exception instanceof \Illuminate\Auth\Access\AuthorizationException)
        {
            abort(401, 'Unauthorized');
        }

        if($exception instanceof \uisits\ipay\Exceptions\IpayExceptionHandler){
            return response()->view('vendor.ipay.ipay', array(
                'exception' => $exception
            ), 500);
        }

        if($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException){
            return response()->view('vendor.ipay.ipay', array(
                'exception' => $exception
            ), 500);
        }

        return parent::render($request, $exception);

    }//end of render function

}//end of class
