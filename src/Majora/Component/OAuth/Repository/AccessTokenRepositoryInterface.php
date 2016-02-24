<?php

namespace Majora\Component\OAuth\Repository;

use Majora\Component\OAuth\Model\AccessTokenInterface;

/**
 * AccessToken storage behavior definition.
 */
interface AccessTokenRepositoryInterface extends TokenRepositoryInterface
{
    /**
     * Store given AccessToken into persistence.
     *
     * @param AccessTokenInterface $accessToken
     */
    public function persist(AccessTokenInterface $accessToken);

    /**
     * Remove given AccessToken from persistence.
     *
     * @param AccessTokenInterface $accessToken
     */
    public function remove(AccessTokenInterface $accessToken);
}
