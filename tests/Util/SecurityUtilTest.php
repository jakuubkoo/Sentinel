<?php

namespace App\Tests\Util;

use App\Util\SecurityUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class SecurityUtilTest
 *
 * Test cases for SecurityUtil class.
 *
 * @package App\Tests\Util
 */
class SecurityUtilTest extends TestCase
{
    private SecurityUtil $securityUtil;

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->securityUtil = new SecurityUtil();
    }

    /**
     * Test the escapeString method of SecurityUtil.
     *
     * @return void
     */
    public function testEscapeString(): void
    {
        // arrange
        $input = '<script>alert("XSS Attack!");</script>';

        // act
        $escapedString = $this->securityUtil->escapeString($input);

        // assert
        $this->assertEquals('&lt;script&gt;alert(&quot;XSS Attack!&quot;);&lt;/script&gt;', $escapedString);
    }

    /**
     * Tests generating an Argon2 hash for a password.
     *
     * @return void
     */
    public function testGenerateHash(): void
    {
        $password = 'testPassword123';
        $hash = $this->securityUtil->generateHash($password);

        // assert that the hash is not false or null
        $this->assertNotFalse($hash);
        $this->assertNotNull($hash);

        // assert that the hash is a valid Argon2 hash
        $info = password_get_info($hash);
        $this->assertEquals('argon2id', $info['algoName']);
    }

    /**
     * Tests verifying a password using an Argon2 hash.
     *
     * @return void
     */
    public function testVerifyPassword(): void
    {
        $password = 'testPassword123';
        $hash = $this->securityUtil->generateHash($password);

        // verify the password with the correct hash
        $this->assertTrue($this->securityUtil->verifyPassword('testPassword123', $hash));

        // verify the password with an incorrect hash
        $this->assertFalse($this->securityUtil->verifyPassword('wrongPassword', $hash));
    }
}
