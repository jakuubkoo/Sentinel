<?php

namespace App\Util;

/**
 * ServiceUtil
 *
 * This class contains utility methods for checking the status of services.
 *
 * @package App\Util
 */
class ServiceUtil
{
    /**
     * Checks if a website is online & response code.
     *
     * @param string $url URL of the website
     * @param int $acceptCode HTTP status code
     *
     * @return bool website online status
     */
    public function checkWebResponseCode(string $url, int $acceptCode): bool
    {
        // check if the website is online
        $headers = @get_headers($url);

        // check if the website is online and the status code is the expected one
        if ($headers && strpos($headers[0], (string) $acceptCode)) {
            return true;
        }

        return false;
    }

    /**
     * Checks if a port is open.
     *
     * @param string $ip Server IP address
     * @param int $port Service port number
     * @param int $maxTimeout Maximal connection check timeout
     *
     * @return bool port open status
     */
    public function isPortOpen(string $ip, int $port, int $maxTimeout = 3): bool
    {
        // check if the port is open
        $fp = @fsockopen($ip, $port, $errno, $errstr, $maxTimeout);
        if ($fp) {
            fclose($fp);
            return true;
        }

        return false;
    }

    /**
     * Checks if a service is or is php extension installed.
     *
     * @param string $serviceName The name of the service.
     *
     * @return bool The service is installed, false otherwise.
     */
    public function isServiceInstalled(string $serviceName): bool
    {
        // check dpkg package
        exec('dpkg -l | grep ' . escapeshellarg($serviceName), $output, $returnCode);

        if ($returnCode === 0) {
            return true;
        }

        // check php extension
        if (extension_loaded($serviceName)) {
            return true;
        }

        return false;
    }

    /**
     * Checks if a service is running with systemctl.
     *
     * @param string $service The name of the service.
     *
     * @return bool The service is running, false otherwise.
     */
    public function isServiceRunningSystemctl(string $service): bool
    {
        // check if service is active
        $output = shell_exec('systemctl is-active ' . $service);

        // check if output is null
        if ($output == null) {
            return false;
        }

        // check if service running
        if (trim($output) == 'active') {
            return true;
        }

        return false;
    }

    /**
     * Checks if a process is running.
     *
     * @param string $process The name of the process.
     *
     * @return bool The process is running, false otherwise.
     */
    public function isProcessRunning(string $process): bool
    {
        exec('pgrep ' . $process, $pids);

        // check if outputed pid
        if (empty($pids)) {
            return true;
        }

        return false;
    }
}
