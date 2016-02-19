<?php

namespace Majora\Component\OAuth\Entity;

use Majora\Component\OAuth\Model\AccessTokenInterface;
use Majora\Component\OAuth\Model\AccountInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Majora\Component\OAuth\Model\RefreshTokenInterface;
use Majora\Component\OAuth\Model\TokenInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

/**
 * Access token class.
 */
class AccessToken extends AbstractToken implements AccessTokenInterface
{
    /**
     * @var RefreshTokenInterface
     */
    protected $refreshToken;

    /**
     * @see AccessTokenInterface::__construct()
     */
    public function __construct(
        ApplicationInterface $application,
        AccountInterface $account = null,
        $expireIn = AccessTokenInterface::DEFAULT_TTL,
        $hash = null,
        RefreshTokenInterface $refreshToken = null
    ) {
        parent::__construct($application, $account, $expireIn, $hash);
        $this->refreshToken = $refreshToken;
    }

    /**
     * @see AccessTokenInterface::getRefreshToken()
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }


}
