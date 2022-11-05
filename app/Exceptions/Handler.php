<?php

namespace App\Exceptions;

use Exception;
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
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function render($request, Exception $exception)
    {
        // どの例外クラスが発生したかによって処理を分ける
        if ($exception instanceof ExclusionException) {
            return redirect()->route('exclusionError', ['code'=>$exception->errorCode]);
        }
        if ($exception instanceof ForbiddenException) {
            return redirect()->route('forbiddenError', ['code'=>$exception->authName]);
        }

        return parent::render($request, $exception);
    }
}
