<?php

namespace App\Middleware;

use App\Manager\ErrorManager;
use Doctrine\DBAL\Connection;
use Exception;

/**
 * Class DatabaseOnlineMiddleware
 *
 * This middleware is used to check the availability of the database.
 *
 * @package App\Middleware
 */
class DatabaseOnlineMiddleware
{
    private ErrorManager $errorManager;
    private Connection $doctrineConnection;

    public function __construct(ErrorManager $errorManager, Connection $doctrineConnection)
    {
        $this->errorManager = $errorManager;
        $this->doctrineConnection = $doctrineConnection;
    }

    /**
     * Check the availability of the database on each kernel request.
     *
     * @return void
     */
    public function onKernelRequest(): void
    {
        try {
            // select for connection try
            $this->doctrineConnection->executeQuery('SELECT 1');
        } catch (Exception $e) {
            // handle error if database not connected
            $this->errorManager->handleError('database connection error: ' . $e->getMessage(), 500);
        }
    }
}
