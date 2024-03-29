<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler; 
use App\ErrorLog;

class Handler extends ExceptionHandler
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
        $ErrorLog = new ErrorLog();
        $ErrorLog->Request = $request;
        $ErrorLog->LogError = $exception;
        $ErrorLog->save();
        
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'success' => 0,
                'message' => 'Method is not allowed for the requested route',
            ], 405);
        }

        return parent::render($request, $exception);
    }
}
