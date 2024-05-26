<?php

namespace App\Tests\Middleware;

use App\Util\SiteUtil;
use App\Manager\ErrorManager;
use PHPUnit\Framework\TestCase;
use App\Middleware\SecurityCheckMiddleware;

/**
 * Class SecurityCheckMiddlewareTest
 *
 * Unit tests for the SecurityCheckMiddleware class.
 *
 * @package App\Tests\Middleware
 */
class SecurityCheckMiddlewareTest extends TestCase
{
    /**
     * Test the behavior of the middleware when SSL is required but not present.
     *
     * @return void
     */
    public function testRequestWhenSslRequiredAndNotSsl(): void
    {
        // mock SiteUtil
        $siteUtilMock = $this->createMock(SiteUtil::class);

        // set up the mock to return true for isSSLOnly and false for isSsl
        $siteUtilMock->method('isSSLOnly')->willReturn(true);
        $siteUtilMock->method('isSsl')->willReturn(false);

        // mock ErrorManager
        $errorManagerMock = $this->createMock(ErrorManager::class);

        // expect handleError method to be called with specific parameterse
        $errorManagerMock->expects($this->once())
            ->method('handleError')
            ->with('SSL error: connection not running on ssl protocol', 500);

        // instantiate the middleware with the mocks
        $middleware = new SecurityCheckMiddleware($siteUtilMock, $errorManagerMock);

        // call the method to be tested
        $middleware->onKernelRequest();
    }

    /**
     * Test the behavior of the middleware when SSL is required and present.
     *
     * @return void
     */
    public function testRequestWhenSslRequiredAndSsl(): void
    {
        // mock SiteUtil
        $siteUtilMock = $this->createMock(SiteUtil::class);

        // set up the mock to return true for isSSLOnly and true for isSsl
        $siteUtilMock->method('isSSLOnly')->willReturn(true);
        $siteUtilMock->method('isSsl')->willReturn(true);

        // mock ErrorManager
        $errorManagerMock = $this->createMock(ErrorManager::class);

        // expect handleError method not to be called
        $errorManagerMock->expects($this->never())->method('handleError');

        // instantiate the middleware with the mocks
        $middleware = new SecurityCheckMiddleware($siteUtilMock, $errorManagerMock);

        // call the method to be tested
        $middleware->onKernelRequest();
    }

    /**
     * Test the behavior of the middleware when SSL is not required.
     *
     * @return void
     */
    public function testRequestWhenSslNotRequired(): void
    {
        // mock SiteUtil
        $siteUtilMock = $this->createMock(SiteUtil::class);

        // set up the mock to return false for isSSLOnly and true for isSsl
        $siteUtilMock->method('isSSLOnly')->willReturn(false);
        $siteUtilMock->method('isSsl')->willReturn(true);

        // mock ErrorManager
        $errorManagerMock = $this->createMock(ErrorManager::class);

        // expect handleError method not to be called
        $errorManagerMock->expects($this->never())->method('handleError');

        // instantiate the middleware with the mocks
        $middleware = new SecurityCheckMiddleware($siteUtilMock, $errorManagerMock);

        // call the method to be tested
        $middleware->onKernelRequest();
    }
}
