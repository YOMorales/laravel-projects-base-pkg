<?php

if (! function_exists('terminate')) {
    /**
     * Combines two Laravel functions: report() and abort().
     *
     * Meant to report/log an exception and also terminate the code execution
     * but using the exception's own status code and message (instead of
     * Laravel's default 500 code).
     */
    function terminate(Throwable $exception): void
    {
        report($exception);

        /*
        Some exceptions (such as Laravel's RequestException class) do have an http status code.
        But other exceptions, like php's ErrorException, do not have it. So here we set a default.
        */
        $code = (int) ($exception->getCode() ?: 500);

        abort($code, $exception->getMessage());
    }
}
