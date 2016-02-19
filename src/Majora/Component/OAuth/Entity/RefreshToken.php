<?php

namespace Majora\Component\OAuth\Entity;

use Majora\Component\OAuth\Model\RefreshTokenInterface;
use Majora\Component\OAuth\Model\AccountInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

/**
 * Class RefreshToken is the default implementation of RefreshTokenInterface
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
class RefreshToken implements RefreshTokenInterface
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
     * @see RefreshTokenInterface::__construct()
     */
    public function __construct(
        ApplicationInterface $application,
        AccountInterface $account = null,
        $expireIn = RefreshTokenInterface::DEFAULT_TTL,
        $hash = null
    ) {
        $this->application = $application;
        $this->account = $account;
        $this->expireIn = $expireIn;

        $this->hash = $hash ?: (new MessageDigestPasswordEncoder())->encodePassword(
            sprintf('[%s\o/%s]', $application->getSecret(), $account->getPassword() ?: time()),
            uniqid(mt_rand(), true)
        );
    }

    /**
     * @see RefreshTokenInterface::getHash()
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @see RefreshTokenInterface::getExpireIn()
     */
    public function getExpireIn()
    {
        return $this->expireIn;
    }

    /**
     * @see RefreshTokenInterface::getAccount()
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @see RefreshTokenInterface::getApplication()
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @see RefreshTokenInterface::getRoles()
     */
    public function getRoles()
    {
        return array_intersect($this->account->getRoles(), $this->application->getRoles());
    }
}
