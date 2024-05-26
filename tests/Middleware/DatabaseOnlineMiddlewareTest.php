<?php

namespace App\Tests\Middleware;

use App\Manager\ErrorManager;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use App\Middleware\DatabaseOnlineMiddleware;

/**
 * Class DatabaseOnlineMiddlewareTest
 *
 * Test cases for DatabaseOnlineMiddleware class.
 *
 * @package App\Tests\Middleware
 */
class DatabaseOnlineMiddlewareTest extends TestCase
{
    /**
     * Test the behavior of DatabaseOnlineMiddleware when the database is online.
     *
     * @return void
     */
    public function testDatabaseOnline(): void
    {
        // mock ErrorManager
        $errorManager = $this->createMock(ErrorManager::class);

        // mock Connection
        $connection = $this->createMock(Connection::class);

        // configure the behavior of the connection mock to return true (online)
        $connection->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT 1');

        // instantiate DatabaseOnlineMiddleware
        $middleware = new DatabaseOnlineMiddleware($errorManager, $connection);

        // call the method to be tested
        $middleware->onKernelRequest();
    }

    /**
     * Test the behavior of DatabaseOnlineMiddleware when the database is offline.
     *
     * @return void
     */
    public function testDatabaseOffline(): void
    {
        // mock ErrorManager
        $errorManager = $this->createMock(ErrorManager::class);

        // mock Connection
        $connection = $this->createMock(Connection::class);

        // configure the behavior of the connection mock to throw an exception (offline)
        $connection->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT 1')
            ->willThrowException(new \Exception('Database offline'));

        // expect a call to handleError method of ErrorManager with the appropriate parameters
        $errorManager->expects($this->once())
            ->method('handleError')
            ->with('database connection error: Database offline', 500);

        // instantiate DatabaseOnlineMiddleware
        $middleware = new DatabaseOnlineMiddleware($errorManager, $connection);

        // call the method to be tested
        $middleware->onKernelRequest();
    }
}
