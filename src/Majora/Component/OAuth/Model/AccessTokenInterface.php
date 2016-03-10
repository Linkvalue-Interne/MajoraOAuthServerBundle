<?php

namespace Majora\Component\OAuth\Model;

/**
 * Interface to implement on entities used as AccessToken.
 */
interface AccessTokenInterface extends TokenInterface
{
    /**
     * Access tokens default TTL.
     */
    const DEFAULT_TTL = 3600;

    /**
     * Construction function.
     *
     * @param ApplicationInterface       $application
     * @param AccountInterface           $account
     * @param int                        $expireIn
     * @param string|null                $hash
     * @param RefreshTokenInterface|null $refreshToken
     */
    public function __construct(
        ApplicationInterface $application,
        AccountInterface $account = null,
        $expireIn = self::DEFAULT_TTL,
        \DateTime $expireAt = null,
        $hash = null,
        RefreshTokenInterface $refreshToken = null
    );

    /**
     * Returns the RefreshToken.
     *
     * @return RefreshTokenInterface|null
     */
    public function getRefreshToken();
}
