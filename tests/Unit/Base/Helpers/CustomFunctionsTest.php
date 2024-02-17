<?php

namespace Tests\Unit\Base\Helpers;

use Exception;
use Tests\TestCase;
use Throwable;

class CustomFunctionsTest extends TestCase
{
    /**
     * Tests the terminate() custom function, which calls Laravel's report() and abort() functions.
     *
     * @test
     */
    public function terminate(): void
    {
        try {
            // original exception; this will be logged and rethrown by the terminate() function
            throw new Exception('Test an exception: Service Unavailable', 503);
        } catch (Throwable $th) {
            // the terminate() function calls abort(), which will rethrow the exception; so we will assert the message
            $this->expectExceptionMessage('Test an exception: Service Unavailable');

            terminate($th);

            /*
            The terminate() function also called report(), which logged the exception.
            But because terminate() aborts the execution of the code, we will need to check the
            logging in a 'finally' block.
            */
        } finally {
            // original exception's message should have been logged to the log handler
            $loggedError = app('log')->getHandlers()[0]->getRecords()[0]['message'];
            $this->assertEquals('Test an exception: Service Unavailable', $loggedError);
        }
    }
}
