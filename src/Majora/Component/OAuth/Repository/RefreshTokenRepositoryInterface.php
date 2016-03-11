<?php

namespace Majora\Component\OAuth\Repository;

use Majora\Component\OAuth\Model\RefreshTokenInterface;

/**
 * RefreshToken storage behavior definition.
 */
interface RefreshTokenRepositoryInterface extends TokenRepositoryInterface
{
    /**
     * Store given RefreshToken into persistence.
     *
     * @param RefreshTokenInterface $accessToken
     */
    public function persist(RefreshTokenInterface $accessToken);

    /**
     * Remove given RefreshToken from persistence.
     *
     * @param RefreshTokenInterface $accessToken
     */
    public function remove(RefreshTokenInterface $accessToken);
}
