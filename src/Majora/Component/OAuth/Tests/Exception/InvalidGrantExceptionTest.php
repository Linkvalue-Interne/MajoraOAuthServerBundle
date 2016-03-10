<?php

namespace Majora\Component\OAuth\Tests\Exception;

use Majora\Component\OAuth\Entity\LoginAttempt;
use Majora\Component\OAuth\Exception\InvalidGrantException;

/**
 * Unit test class for Majora\Component\OAuth\Exception\InvalidGrantException.
 */
class InvalidGrantExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests all class methods.
     */
    public function testClass()
    {
        $exception = new InvalidGrantException(
            $loginAttempt = $this->prophesize(LoginAttempt::class)->reveal(),
            $message = 'Fake exception',
            $code = 666,
            $previous = new \Exception()
        );

        $this->assertSame($loginAttempt, $exception->getLoginAttempt());
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
