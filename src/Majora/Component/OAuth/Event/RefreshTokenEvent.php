<?php

namespace Majora\Component\OAuth\Event;

use Majora\Component\OAuth\Model\RefreshTokenInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * RefreshToken specific event class.
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
class RefreshTokenEvent extends Event
{
    /**
     * @var RefreshTokenInterface
     */
    protected $refreshToken;

    /**
     * Construct.
     *
     * @param RefreshTokenInterface $refreshToken
     */
    public function __construct(RefreshTokenInterface $refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    /**
     * return related access token.
     *
     * @return RefreshTokenInterface
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }
}
