<?php

namespace App\Exceptions;

use App\ResponseError;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            Log::error($e->getMessage(), [
                'trace' => $e->getTrace()
            ]);
            return false;
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return ResponseError::methodNotAllowed();
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
                return ResponseError::resourceNotFound();
        });

        $this->renderable(function (Exception $e, $request) {
            return ResponseError::error($e);
        });
    }
}
