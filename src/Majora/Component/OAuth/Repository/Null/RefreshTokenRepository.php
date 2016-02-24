<?php

namespace Majora\Component\OAuth\Repository\Null;

use Majora\Component\OAuth\Model\RefreshTokenInterface;
use Majora\Component\OAuth\Repository\RefreshTokenRepositoryInterface;

/**
 * RefreshToken repository empty implementation.
 *
 * @see Majora\Component\OAuth\Repository\TokenRepositoryInterface
 */
class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * @see TokenRepositoryInterface::persist()
     */
    public function persist(RefreshTokenInterface $refreshToken)
    {
    }

    /**
     * @see TokenRepositoryInterface::remove()
     */
    public function remove(RefreshTokenInterface $refreshToken)
    {
    }
}
