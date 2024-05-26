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
    /**
     * Test the escapeString method of SecurityUtil.
     * 
     * @return void
     */
    public function testEscapeString(): void
    {
        // arrange
        $securityUtil = new SecurityUtil();
        $input = '<script>alert("XSS Attack!");</script>';

        // act
        $escapedString = $securityUtil->escapeString($input);

        // assert
        $this->assertEquals('&lt;script&gt;alert(&quot;XSS Attack!&quot;);&lt;/script&gt;', $escapedString);
    }
}
