<?php

namespace App\Tests\Manager;

use App\Manager\ErrorManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ErrorManagerTest
 *
 * @package App\Tests\Manager
 */
class ErrorManagerTest extends TestCase
{
    private ErrorManager $errorManager;

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->errorManager = new ErrorManager();
    }

    /**
     * Tests the handleError method to ensure it throws the expected HttpException.
     *
     * @return void
     */
    public function testHandleError(): void
    {
        // set up test data
        $msg = 'Test error message';
        $code = 404;

        // expect an HttpException with the specified message and code
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage($msg);
        $this->expectExceptionCode($code);

        // call the method under test
        $this->errorManager->handleError($msg, $code);
    }
}
