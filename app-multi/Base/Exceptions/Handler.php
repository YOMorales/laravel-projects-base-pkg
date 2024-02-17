<?php

namespace App\Base\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * The closure in this method (that handles Throwable) will get called whenever
     * we use the report() function and also on uncaught exceptions.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // then does the Laravel default error handling, which is to log
    }
}
