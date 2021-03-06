<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
            $response = [
                'success' => false,
            ];
            $status = $this->isHttpException($exception) ? $exception->getStatusCode() : 500;

            if ($exception instanceof ModelNotFoundException) {
                $response['message'] = 'Record not found';
                $status = 404;
            }

            if ($exception instanceof ValidationException) {
                $response['message'] = $exception->validator->messages();
                $status = 422;
            }

            if ($exception instanceof AuthenticationException) {
                $response['message'] = 'Usuario o password no validos';
                $status = 401;
            }

            return response()->json($response, $status);
        }

        return parent::render($request, $exception);
    }
}
