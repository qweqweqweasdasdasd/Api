<?php

namespace App\Exceptions;

use Exception;
use App\ErrDesc\ApiErrDesc;
use App\Response\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        // 如果是 api 路由
        if($request->is('api/*')){

            if($exception instanceof ApiException){
                $code = $exception->getCode();
                $msg  = $exception->getMessage();
               
            }else{
                $code = $exception->getCode();
                if(!$code || $code < 0){
                    $code = ApiErrDesc::SERVER_ERR[0];
                }
                $msg = $exception->getMessage() ?: ApiErrDesc::SERVER_ERR[1];
            }
            return ApiResponse::JsonData($code,$msg);
        }
        return parent::render($request, $exception);
    }
}
