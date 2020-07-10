<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-10 11:20:05
 * @LastEditTime: 2020-07-10 11:20:12
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Exceptions/Handler.php
 * @Motto: MMMMMMMM
 */

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

// use App\Traits\Respond;

class Handler extends ExceptionHandler
{
    // use Respond;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
        CodeException::class,
        TryException::class,
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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException){
            if ($exception->getStatusCode() == 404) {
                // dd(response());
                return response()->view('error.404');
                // return $this->response();//response()->view('welcome');
            }
        }

        if ($exception instanceof CodeException) {
            $code = $exception->getCode();
            $content = config('message.'.$code);
            return \Response::json(['message' => $content,'code' => $code,'data' => (object)null],200);
        }

        if ($exception instanceof TryException) {
            $codeMessage = $exception->getMessage();
            $content = config('message.400000002');
            return \Response::json(['message' => $content,'code' => 400000002,'code_message' => $codeMessage,'data' => (object)null],200);
        }

        return parent::render($request, $exception);
    }
}
