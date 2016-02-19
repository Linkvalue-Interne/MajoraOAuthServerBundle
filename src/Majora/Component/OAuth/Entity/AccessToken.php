<?php

namespace Majora\Component\OAuth\Entity;

use Majora\Component\OAuth\Model\AccessTokenInterface;
use Majora\Component\OAuth\Model\AccountInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Majora\Component\OAuth\Model\RefreshTokenInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

/**
 * Access token class.
 */
class AccessToken implements AccessTokenInterface
{
    /**
     * @var string
     */
    protected $hash;

    /**
     * @var int
     */
    protected $expireIn;

    /**
     * @var AccountInterface
     */
    protected $account;

    /**
     * @var ApplicationInterface
     */
    protected $application;

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
        $this->application = $application;
        $this->account = $account;
        $this->expireIn = $expireIn;
        $this->refreshToken = $refreshToken;

        $this->hash = $hash ?: (new MessageDigestPasswordEncoder())->encodePassword(
            sprintf('[%s\o/%s]', $application->getSecret(), $account->getPassword() ?: time()),
            uniqid(mt_rand(), true)
        );
    }

    /**
     * @see AccessTokenInterface::getHash()
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @see AccessTokenInterface::getExpireIn()
     */
    public function getExpireIn()
    {
        return $this->expireIn;
    }

    /**
     * @see AccessTokenInterface::getAccount()
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @see AccessTokenInterface::getApplication()
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @see AccessTokenInterface::getRoles()
     */
    public function getRoles()
    {
        return array_intersect($this->account->getRoles(), $this->application->getRoles());
    }

    /**
     * @see AccessTokenInterface::getRefreshToken()
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }


}
