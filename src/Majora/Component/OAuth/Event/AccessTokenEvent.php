<?php

namespace Majora\Component\OAuth\Event;

use Majora\Component\OAuth\Entity\AccessToken;
use Symfony\Component\EventDispatcher\Event;

/**
 * AccessToken specific event class.
 */
class AccessTokenEvent extends Event
{
    /**
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * construct.
     *
     * @param AccessToken $accessToken
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * return related access token.
     *
     * @return AccessToken
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}
