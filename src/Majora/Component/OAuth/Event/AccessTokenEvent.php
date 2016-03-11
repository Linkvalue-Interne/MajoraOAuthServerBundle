<?php

namespace Majora\Component\OAuth\Event;

use Majora\Component\OAuth\Model\AccessTokenInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * AccessTokenInterface specific event class.
 */
class AccessTokenEvent extends Event
{
    /**
     * @var AccessTokenInterface
     */
    protected $accessToken;

    /**
     * construct.
     *
     * @param AccessTokenInterface $accessToken
     */
    public function __construct(AccessTokenInterface $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * return related access token.
     *
     * @return AccessTokenInterface
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}
