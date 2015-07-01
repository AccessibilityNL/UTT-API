<?php namespace App\Exceptions;

use Exception;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        'Symfony\Component\HttpKernel\Exception\HttpException'
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($request->isJson()) {

//            dd($e);
            $message = str_replace("App\\Models\\", "", $e->getMessage());

            if($e instanceof NotFoundHttpException)
                $code = 404;
            else
                $code = $e->getCode() == 0 ? 422 : $e->getCode();

            if($code > 500 || $code < 200)
                $code = 422;
            return response(['code' => $code, 'message' => $message], $code);
        } else {
            return parent::render($request, $e);
        }
    }

}
