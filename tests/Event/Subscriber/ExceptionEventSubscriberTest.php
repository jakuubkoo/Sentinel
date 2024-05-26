<?php

namespace App\Tests\Event\Subscriber;

use App\Util\SiteUtil;
use App\Manager\LogManager;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Event\Subscriber\ExceptionEventSubscriber;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class ExceptionEventSubscriberTest
 *
 * Test cases for ExceptionEventSubscriber class.
 *
 * @package App\Tests\Event\Subscriber
 */
class ExceptionEventSubscriberTest extends TestCase
{
    private MockObject|SiteUtil $siteUtil;
    private MockObject|LogManager $logManager;
    private MockObject|LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->siteUtil = $this->createMock(SiteUtil::class);
        $this->logManager = $this->createMock(LogManager::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    /**
     * Test the onKernelException method of ExceptionEventSubscriber when not in dev mode.
     *
     * @return void
     */
    public function testOnKernelException(): void
    {
        // create a new exception object
        $exception = new \Exception('Test exception message', 500);

        // create a new ExceptionEvent instance with our test exception object
        $event = new ExceptionEvent(
            $this->createMock('Symfony\Component\HttpKernel\HttpKernelInterface'),
            $this->createMock('Symfony\Component\HttpFoundation\Request'),
            1,
            $exception
        );

        // set up mock expectations
        $this->siteUtil->expects($this->once())->method('isDevMode')->willReturn(false);
        $this->logManager->expects($this->once())->method('log')->with('exception', $exception->getMessage());
        $this->logger->expects($this->once())->method('error')->with($exception->getMessage());

        // create a new ExceptionEventSubscriber instance and call the onKernelException method
        $subscriber = new ExceptionEventSubscriber($this->siteUtil, $this->logManager, $this->logger);
        $subscriber->onKernelException($event);
    }

    /**
     * Test the onKernelException method of ExceptionEventSubscriber when in dev mode.
     *
     * @return void
     */
    public function testOnKernelExceptionWithDevMode(): void
    {
        // create a new exception object
        $exception = new \Exception('Test exception message', 500);

        // create a new ExceptionEvent instance with our test exception object
        $event = new ExceptionEvent(
            $this->createMock('Symfony\Component\HttpKernel\HttpKernelInterface'),
            $this->createMock('Symfony\Component\HttpFoundation\Request'),
            1,
            $exception
        );

        // set up mock expectations
        $this->siteUtil->expects($this->once())->method('isDevMode')->willReturn(true);
        $this->logManager->expects($this->once())->method('log')->with('exception', $exception->getMessage());
        $this->logger->expects($this->once())->method('error')->with($exception->getMessage());

        // create a new ExceptionEventSubscriber instance and call the onKernelException method
        $subscriber = new ExceptionEventSubscriber($this->siteUtil, $this->logManager, $this->logger);
        $subscriber->onKernelException($event);
    }
}
