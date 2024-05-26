<?php

namespace App\Tests\Util;

use App\Util\SiteUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class SiteUtilTest
 *
 * Unit tests for the SiteUtil class.
 *
 * @package App\Tests\Util
 */
class SiteUtilTest extends TestCase
{
    private SiteUtil $siteUtil;

    protected function setUp(): void
    {
        $this->siteUtil = new SiteUtil();
    }

    /**
     * Tests the isDevMode method when the environment is set to 'dev'.
     *
     * @return void
     */
    public function testIsDevModeWithDevEnv(): void
    {
        // set the environment to 'dev'
        $_ENV['APP_ENV'] = 'dev';

        // assert that the method returns true
        $this->assertTrue($this->siteUtil->isDevMode());
    }

    /**
     * Tests the isDevMode method when the environment is set to 'test'.
     *
     * @return void
     */
    public function testIsDevModeWithTestEnv(): void
    {
        // set the environment to 'test'
        $_ENV['APP_ENV'] = 'test';

        // assert that the method returns true
        $this->assertTrue($this->siteUtil->isDevMode());
    }

    /**
     * Tests the isDevMode method when the environment is set to 'prod'.
     *
     * @return void
     */
    public function testIsDevModeWithProdEnv(): void
    {
        // set the environment to 'prod'
        $_ENV['APP_ENV'] = 'prod';

        // assert that the method returns false
        $this->assertFalse($this->siteUtil->isDevMode());
    }

    /**
     * Tests the isDevMode method when the environment is set to an unknown value.
     *
     * @return void
     */
    public function testIsDevModeWithUnknownEnv(): void
    {
        // set the environment to an unknown value
        $_ENV['APP_ENV'] = 'unknown';

        // assert that the method returns false
        $this->assertFalse($this->siteUtil->isDevMode());
    }
}
