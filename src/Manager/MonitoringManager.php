<?php

namespace App\Manager;

use App\Entity\Service;
use App\Util\ServiceUtil;

/**
 * Class MonitoringManager
 *
 * The MonitoringManager class is responsible for monitoring all services.
 *
 * @package App\Manager
 */
class MonitoringManager
{
    private ServiceUtil $serviceUtil;
    private ServiceManager $serviceManager;

    public function __construct(ServiceUtil $serviceUtil, ServiceManager $serviceManager)
    {
        $this->serviceUtil = $serviceUtil;
        $this->serviceManager = $serviceManager;
    }

    /**
     * Monitor all services.
     *
     * @return void
     */
    public function monitorInit(): void
    {
        $services = $this->serviceManager->getAllServices();

        // monitor all services
        foreach ($services as $service) {
            $this->monitorService($service);
        }
    }

    /**
     * Monitor a service.
     *
     * @param Service $service
     *
     * @return void
     */
    public function monitorService(Service $service): void
    {
        // get service data
        $type = $service->getType();
        $target = $service->getTarget();
        $port = $service->getPort();
        $maxTimeout = $service->getMaxTimeout();
        $httpAcceptCode = $service->getHttpAcceptCode();

        // fix target url format
        $target = rtrim($target, '/');

        // default service status
        $status = 'offline';

        // check if monitoring service is http/web app
        if (
            str_contains($type, 'http') or
            str_contains($type, 'web') or
            str_contains($type, 'api')
        ) {
            // check if service is online
            if ($this->serviceUtil->checkWebResponseCode($target . ':' . $port, $httpAcceptCode, $maxTimeout)) {
                $status = 'online';
            }
        } else {
            // check socket connection
            if ($this->serviceUtil->isPortOpen($target, $port, $maxTimeout)) {
                $status = 'online';
            }
        }

        // update service status
        $this->serviceManager->updateServiceStatus($service, $status);
    }
}
