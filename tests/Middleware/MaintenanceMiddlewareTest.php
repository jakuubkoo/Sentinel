<?php

namespace App\Tests\Middleware;

use App\Util\SiteUtil;
use PHPUnit\Framework\TestCase;
use App\Middleware\MaintenanceMiddleware;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Class MaintenanceMiddlewareTest
 *
 * PHPUnit test case for the MaintenanceMiddleware class.
 *
 * @package App\Tests\Middleware
 */
class MaintenanceMiddlewareTest extends TestCase
{
    /**
     * Test case for onKernelRequest method when maintenance mode is enabled.
     *
     * @return void
     */
    public function testRequestWhenMaintenanceModeEnabled(): void
    {
        // mock SiteUtil to return true for isMaintenance()
        $siteUtilMock = $this->createMock(SiteUtil::class);
        $siteUtilMock->expects($this->once())
            ->method('isMaintenance')
            ->willReturn(true);

        // create MaintenanceMiddleware instance with the mocked SiteUtil
        $middleware = new MaintenanceMiddleware($siteUtilMock);

        // create a RequestEvent mock
        $requestEventMock = $this->createMock(RequestEvent::class);

        // expect that setResponse method will be called once with a JsonResponse
        $requestEventMock->expects($this->once())
            ->method('setResponse')
            ->with($this->isInstanceOf(JsonResponse::class));

        // call onKernelRequest method of middleware with the RequestEvent mock
        $middleware->onKernelRequest($requestEventMock);
    }

    /**
     * Test case for onKernelRequest method when maintenance mode is disabled.
     *
     * @return void
     */
    public function testRequestWhenMaintenanceModeDisabled(): void
    {
        // mock SiteUtil to return false for isMaintenance()
        $siteUtilMock = $this->createMock(SiteUtil::class);
        $siteUtilMock->expects($this->once())
            ->method('isMaintenance')
            ->willReturn(false);

        // create MaintenanceMiddleware instance with the mocked SiteUtil
        $middleware = new MaintenanceMiddleware($siteUtilMock);

        // create a RequestEvent mock
        $requestEventMock = $this->createMock(RequestEvent::class);

        // expect that setResponse method will not be called
        $requestEventMock->expects($this->never())
            ->method('setResponse');

        // call onKernelRequest method of middleware with the RequestEvent mock
        $middleware->onKernelRequest($requestEventMock);
    }
}
