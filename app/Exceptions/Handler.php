<?php

namespace App\Exceptions;

use App\Exceptions\ModelExceptions\User\NotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (HttpException $e) {
            if ($e instanceof NotFoundHttpException) {
                $message = 'Resource not found.';
            } else {
                $message = $e->getMessage();
            }

            if (request()->is('v1/*')) {
                return response()->json([
                    'error' => true,
                    'message' => $message
                ], $e->getStatusCode(), options: JSON_UNESCAPED_UNICODE);
            }

            if ($e instanceof NotFoundHttpException) {
                return response()->view('errors.404', status: 404);
            } elseif ($e instanceof UnauthorizedHttpException) {
                return response()->view('errors.401', status: 401);
            } else {
                return response()->view('errors.500', status: 500);
            }
        });

        $this->reportable(function (Throwable $e) {
            $payload = [
                'meta' => [
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];

            if (method_exists($e, 'context')) {
                $payload = array_merge($payload, $e->context());
            }

            if ($e instanceof ValidationException) {
                $message = $e->validator->errors()->first() ?? 'The given data was invalid.';
            } elseif ($e->getMessage()) {
                $message = $e->getMessage();
            } elseif (property_exists($e, 'defaultMessage')) {
                $message = $e->defaultMessage;
            } else {
                $message = 'Internal Server Error.';
            }

            Log::error($message, $payload);

            return false;
        });

        $this->renderable(function (Throwable $e) {
            if (property_exists($e, 'status')) {
                $status = $e->status;
            } else {
                $status = 500;
            }

            if ($e instanceof ValidationException) {
                $message = $e->validator->errors()->first() ?? 'The given data was invalid.';
            } elseif ($e->getMessage()) {
                $message = $e->getMessage();
            } elseif (property_exists($e, 'defaultMessage')) {
                $message = $e->defaultMessage;
            } else {
                $message = 'Internal Server Error.';
            }

            $response = [
                'error' => true,
                'message' => $message
            ];

            if (App::environment('local')) {
                $response['exception'] = get_class($e);
                $response['file'] = $e->getFile();
                $response['line'] = $e->getLine();
                $response['trace'] = $e->getTrace();
            }

            return response()->json($response, $status, options: JSON_UNESCAPED_UNICODE);
        });
    }
}
