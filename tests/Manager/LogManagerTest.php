<?php

namespace App\Tests\Manager;

use App\Entity\Log;
use App\Manager\LogManager;
use App\Manager\ErrorManager;
use App\Util\VisitorInfoUtil;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class LogManagerTest
 *
 * Unit tests for the LogManager class.
 *
 * @package App\Tests\Manager
 */
class LogManagerTest extends TestCase
{
    private LogManager $logManager;
    private MockObject|ErrorManager $errorManager;
    private MockObject|VisitorInfoUtil $visitorInfoUtil;
    private MockObject|EntityManagerInterface $entityManager;

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->errorManager = $this->createMock(ErrorManager::class);
        $this->visitorInfoUtil = $this->createMock(VisitorInfoUtil::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->logManager = new LogManager($this->errorManager, $this->visitorInfoUtil, $this->entityManager);
    }

    /**
     * Tests the log method when logging is successful.
     *
     * This method verifies that the log entity is persisted and flushed to the database
     * when logging is successful.
     *
     * @return void
     */
    public function testLogSuccess(): void
    {
        // set up test data
        $name = 'test_name';
        $message = 'test_message';
        $ipAddress = '127.0.0.1';

        // set up mock expectations
        $this->visitorInfoUtil->method('getIP')->willReturn($ipAddress);
        $this->entityManager->expects($this->once())->method('persist')->with($this->isInstanceOf(Log::class));
        $this->entityManager->expects($this->once())->method('flush');

        // call the method under test
        $this->logManager->log($name, $message);
    }

    /**
     * Tests the log method when logging fails.
     *
     * This method verifies that an error is handled when logging fails due to an exception
     * during the persistence process.
     *
     * @return void
     */
    public function testLogFailure(): void
    {
        // set up test data
        $name = 'test_name';
        $message = 'test_message';
        $ipAddress = '127.0.0.1';

        // set up mock expectations
        $this->visitorInfoUtil->method('getIP')->willReturn($ipAddress);
        $this->entityManager->method('persist')->will($this->throwException(new \Exception('persist-error')));
        $this->errorManager->expects($this->once())->method('handleError')->with($this->stringContains('log-error: persist-error'), 500);

        // call the method under test
        $this->logManager->log($name, $message);
    }
}
