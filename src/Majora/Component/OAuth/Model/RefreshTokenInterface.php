<?php
namespace Majora\Component\OAuth\Model;

/**
 * Interface to implement on entities used as RefreshToken.
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
interface RefreshTokenInterface
{
    /**
     * Refresh tokens default TTL (4 weeks).
     */
    const DEFAULT_TTL = 2419200;

    /**
     * Construction function.
     *
     * @param ApplicationInterface $application
     * @param AccountInterface     $account
     * @param int                  $expireIn
     */
    public function __construct(
        ApplicationInterface $application,
        AccountInterface $account = null,
        $expireIn = self::DEFAULT_TTL
    );

    /**
     * Returns refresh token hash as string.
     *
     * @return string
     */
    public function getHash();

    /**
     * Returns refresh token ttl in sec.
     *
     * @return int
     */
    public function getExpireIn();

    /**
     * Returns refresh token related account.
     *
     * @return AccountInterface|null
     */
    public function getAccount();

    /**
     * Returns refresh token related client.
     *
     * @return ApplicationInterface
     */
    public function getApplication();

    /**
     * Returns refresh token roles, compiled from Application and Account roles.
     *
     * @return array
     */
    public function getRoles();
}