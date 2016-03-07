<?php

namespace Majora\Component\OAuth\Tests\Event;

use Majora\Component\OAuth\Event\AccessTokenEvent;
use Majora\Component\OAuth\Model\AccessTokenInterface;

/**
 * Unit test class for Majora\Component\OAuth\Event\AccessTokenEvent.
 */
class AccessTokenEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests getAccessToken() method.
     */
    public function testAccessToken()
    {
        $accessToken = $this->prophesize(AccessTokenInterface::class)->reveal();

        $event = new AccessTokenEvent($accessToken);

        $this->assertSame($accessToken, $event->getAccessToken());
    }
}
