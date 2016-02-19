<?php
namespace Majora\Component\OAuth\Model;

/**
 * Interface to implement on entities used as RefreshToken.
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
interface RefreshTokenInterface extends TokenInterface
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
     * @param string               $hash
     */
    public function __construct(
        ApplicationInterface $application,
        AccountInterface $account = null,
        $expireIn = self::DEFAULT_TTL,
        $hash = null
    );
}