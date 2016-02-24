<?php

namespace Majora\Component\OAuth\Repository\Null;

use Majora\Component\OAuth\Model\AccessTokenInterface;
use Majora\Component\OAuth\Repository\AccessTokenRepositoryInterface;

/**
 * AccessToken repository empty implementation.
 *
 * @see Majora\Component\OAuth\Repository\TokenRepositoryInterface
 */
class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * @see TokenRepositoryInterface::persist()
     */
    public function persist(AccessTokenInterface $accessToken)
    {
    }

    /**
     * @see TokenRepositoryInterface::remove()
     */
    public function remove(AccessTokenInterface $accessToken)
    {
    }
}
