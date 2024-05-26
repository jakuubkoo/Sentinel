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

    /**
     * Test the isSsl method of SiteUtil.
     *
     * @return void
     */
    public function testIsSsl(): void
    {
        // Arrange
        $_SERVER['HTTPS'] = 1;
        $siteUtil = new SiteUtil();

        // Act & Assert
        $this->assertTrue($siteUtil->isSsl());

        // Arrange
        $_SERVER['HTTPS'] = 'on';

        // Act & Assert
        $this->assertTrue($siteUtil->isSsl());

        // Arrange
        $_SERVER['HTTPS'] = null;

        // Act & Assert
        $this->assertFalse($siteUtil->isSsl());
    }

    /**
     * Test the isSSLOnly method of SiteUtil.
     *
     * @return void
     */
    public function testIsSSLOnly(): void
    {
        // Arrange
        $_ENV['SSL_ONLY'] = 'true';
        $siteUtil = new SiteUtil();

        // Act & Assert
        $this->assertTrue($siteUtil->isSSLOnly());

        // Arrange
        $_ENV['SSL_ONLY'] = 'false';

        // Act & Assert
        $this->assertFalse($siteUtil->isSSLOnly());
    }

    /**
     * Test the isMaintenance method of SiteUtil.
     *
     * @return void
     */
    public function testIsMaintenanceEnabled(): void
    {
        // mock $_ENV['MAINTENANCE_MODE']
        $_ENV['MAINTENANCE_MODE'] = 'true';

        // act
        $result = $this->siteUtil->isMaintenance();

        // assert
        $this->assertTrue($result);
    }

    /**
     * Test the isMaintenance method of SiteUtil.
     *
     * @return void
     */
    public function testIsMaintenanceDisabled(): void
    {
        // mock $_ENV['MAINTENANCE_MODE']
        $_ENV['MAINTENANCE_MODE'] = 'false';

        // act
        $result = $this->siteUtil->isMaintenance();

        // assert
        $this->assertFalse($result);
    }
}
