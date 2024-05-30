<?php

namespace App\Tests\Manager;

use App\Entity\Service;
use App\Util\ServiceUtil;
use PHPUnit\Framework\TestCase;
use App\Manager\ServiceManager;
use App\Manager\MonitoringManager;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class MonitoringManagerTest
 *
 * The MonitoringManagerTest class is responsible for testing the MonitoringManager class.
 *
 * @package App\Tests\Manager
 */
class MonitoringManagerTest extends TestCase
{
    private MockObject|ServiceUtil $serviceUtilMock;
    private MockObject|ServiceManager $serviceManagerMock;
    private MockObject|MonitoringManager $monitoringManager;

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->serviceUtilMock = $this->createMock(ServiceUtil::class);
        $this->serviceManagerMock = $this->createMock(ServiceManager::class);

        $this->monitoringManager = new MonitoringManager($this->serviceUtilMock, $this->serviceManagerMock);
    }

    /**
     * Test monitor init.
     *
     * @return void
     */
    public function testMonitorInit(): void
    {
        $serviceMock = $this->createMock(Service::class);

        // set up the service mock
        $serviceMock->method('getType')->willReturn('http');
        $serviceMock->method('getTarget')->willReturn('http://example.com');
        $serviceMock->method('getPort')->willReturn(80);
        $serviceMock->method('getMaxTimeout')->willReturn(30);
        $serviceMock->method('getHttpAcceptCode')->willReturn(200);

        // set up the service manager mock
        $this->serviceManagerMock->method('getAllServices')->willReturn([$serviceMock]);

        // set up the service util mock
        $this->serviceUtilMock->method('checkWebResponseCode')->willReturn(true);

        // assert that the service manager updateServiceStatus method is called
        $this->serviceManagerMock->expects($this->once())->method('getAllServices');
        $this->serviceManagerMock->expects($this->once())->method('updateServiceStatus')->with($serviceMock, 'online');

        // call the monitorInit method
        $this->monitoringManager->monitorInit();
    }

    /**
     * Test monitor service online http.
     *
     * @return void
     */
    public function testMonitorServiceOnlineHttp(): void
    {
        $serviceMock = $this->createMock(Service::class);

        // set up the service mock
        $serviceMock->method('getType')->willReturn('http');
        $serviceMock->method('getTarget')->willReturn('http://example.com');
        $serviceMock->method('getPort')->willReturn(80);
        $serviceMock->method('getMaxTimeout')->willReturn(30);
        $serviceMock->method('getHttpAcceptCode')->willReturn(200);

        // set up the service util mock
        $this->serviceUtilMock->method('checkWebResponseCode')->with('http://example.com:80', 200, 30)->willReturn(true);

        // assert that the service manager updateServiceStatus method is called
        $this->serviceManagerMock->expects($this->once())->method('updateServiceStatus')->with($serviceMock, 'online');

        // call the monitorService method
        $this->monitoringManager->monitorService($serviceMock);
    }

    /**
     * Test monitor service offline http.
     *
     * @return void
     */
    public function testMonitorServiceOfflineHttp(): void
    {
        $serviceMock = $this->createMock(Service::class);

        // set up the service mock
        $serviceMock->method('getType')->willReturn('http');
        $serviceMock->method('getTarget')->willReturn('http://example.com');
        $serviceMock->method('getPort')->willReturn(80);
        $serviceMock->method('getMaxTimeout')->willReturn(30);
        $serviceMock->method('getHttpAcceptCode')->willReturn(200);

        // set up the service util mock
        $this->serviceUtilMock->method('checkWebResponseCode')->with('http://example.com:80', 200, 30)->willReturn(false);

        // assert that the service manager updateServiceStatus method is called
        $this->serviceManagerMock->expects($this->once())->method('updateServiceStatus')->with($serviceMock, 'offline');

        // call the monitorService method
        $this->monitoringManager->monitorService($serviceMock);
    }

    /**
     * Test monitor service online socket.
     *
     * @return void
     */
    public function testMonitorServiceOnlineSocket(): void
    {
        $serviceMock = $this->createMock(Service::class);

        // set up the service mock
        $serviceMock->method('getType')->willReturn('socket');
        $serviceMock->method('getTarget')->willReturn('127.0.0.1');
        $serviceMock->method('getPort')->willReturn(8080);
        $serviceMock->method('getMaxTimeout')->willReturn(30);

        // set up the service util mock
        $this->serviceUtilMock->method('isPortOpen')->with('127.0.0.1', 8080, 30)->willReturn(true);

        // assert that the service manager updateServiceStatus method is called
        $this->serviceManagerMock->expects($this->once())->method('updateServiceStatus')->with($serviceMock, 'online');

        // call the monitorService method
        $this->monitoringManager->monitorService($serviceMock);
    }

    /**
     * Test monitor service offline socket.
     *
     * @return void
     */
    public function testMonitorServiceOfflineSocket(): void
    {
        $serviceMock = $this->createMock(Service::class);

        // set up the service mock
        $serviceMock->method('getType')->willReturn('socket');
        $serviceMock->method('getTarget')->willReturn('127.0.0.1');
        $serviceMock->method('getPort')->willReturn(8080);
        $serviceMock->method('getMaxTimeout')->willReturn(30);

        // set up the service util mock
        $this->serviceUtilMock->method('isPortOpen')->with('127.0.0.1', 8080, 30)->willReturn(false);

        // assert that the service manager updateServiceStatus method is called
        $this->serviceManagerMock->expects($this->once())->method('updateServiceStatus')->with($serviceMock, 'offline');

        // call the monitorService method
        $this->monitoringManager->monitorService($serviceMock);
    }
}
