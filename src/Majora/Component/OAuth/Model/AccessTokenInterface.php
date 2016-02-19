<?php

namespace Majora\Component\OAuth\Model;

/**
 * Interface to implement on entities used as AccessToken.
 */
interface AccessTokenInterface
{
    /**
     * Access tokens default TTL.
     */
    const DEFAULT_TTL = 3600;

    /**
     * Construction function.
     *
     * @param ApplicationInterface $application
     * @param AccountInterface     $account
     * @param int                  $expireIn
     * @param string|null          $hash
     */
    public function __construct(
        ApplicationInterface $application,
        AccountInterface $account = null,
        $expireIn = self::DEFAULT_TTL,
        $hash = null
    );

    /**
     * Returns access token hash as string.
     *
     * @return string
     */
    public function getHash();

    /**
     * Returns access token ttl in sec.
     *
     * @return int
     */
    public function getExpireIn();

    /**
     * Returns access token related account.
     *
     * @return AccountInterface|null
     */
    public function getAccount();

    /**
     * Returns access token related client.
     *
     * @return ApplicationInterface
     */
    public function getApplication();

    /**
     * Returns access token roles, compiled from Application and Account roles.
     *
     * @return array
     */
    public function getRoles();

    /**
     * Returns the RefreshToken
     *
     * @return RefreshTokenInterface|null
     */
    public function getRefreshToken();
}
