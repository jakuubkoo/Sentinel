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
}
