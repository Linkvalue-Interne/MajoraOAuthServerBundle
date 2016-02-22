<?php

namespace Majora\Component\OAuth\Entity;

use Majora\Component\OAuth\Model\TokenInterface;
use Majora\Component\OAuth\Model\AccountInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

/**
 * Abstract token class.
 */
abstract class Token implements TokenInterface
{
    /**
     * @var int
     */
    protected $id;

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
     * @see TokenInterface::__construct()
     */
    public function __construct(
        ApplicationInterface $application,
        AccountInterface $account = null,
        $expireIn = TokenInterface::DEFAULT_TTL,
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
     * @see TokenInterface::__toString()
     */
    public function __toString()
    {
        return $this->hash;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @see TokenInterface::getHash()
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @see TokenInterface::getExpireIn()
     */
    public function getExpireIn()
    {
        return $this->expireIn;
    }

    /**
     * @see TokenInterface::getAccount()
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @see TokenInterface::getApplication()
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @see TokenInterface::getRoles()
     */
    public function getRoles()
    {
        return array_intersect($this->account->getRoles(), $this->application->getRoles());
    }
}
