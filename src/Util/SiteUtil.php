<?php

namespace App\Util;

/**
 * Class SiteUtil
 *
 * SiteUtil provides basic site-related methods.
 *
 * @package App\Util
 */
class SiteUtil
{
    /**
     * Check if the application is in development mode.
     *
     * @return bool Whether the application is in development mode.
     */
    public function isDevMode(): bool
    {
        if ($_ENV['APP_ENV'] == 'dev' || $_ENV['APP_ENV'] == 'test') {
            return true;
        }

        return false;
    }

    /**
     * Check if the connection is secure (SSL).
     *
     * @return bool Whether the connection is secure.
     */
    public function isSsl(): bool
    {
        // check if HTTPS header is set and its value is either 1 or 'on'
        return isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 1 || strtolower($_SERVER['HTTPS']) === 'on');
    }

    /**
     * Check if the ssl only mode.
     *
     * @return bool Whether the application is under ssl only mode.
     */
    public function isSSLOnly(): bool
    {
        return $_ENV['SSL_ONLY'] === 'true';
    }

    /**
     * Check if the application is in maintenance mode.
     *
     * @return bool Whether the application is in maintenance mode.
     */
    public function isMaintenance(): bool
    {
        return $_ENV['MAINTENANCE_MODE'] === 'true';
    }

    /**
     * Check if emailing is enabled.
     *
     * @return bool Whether emailing is enabled.
     */
    public function isEmailingEnabled(): bool
    {
        return $_ENV['SEND_EMAILS'] === 'true';
    }
}
