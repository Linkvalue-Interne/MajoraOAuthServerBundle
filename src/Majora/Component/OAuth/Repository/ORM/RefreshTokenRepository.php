<?php

namespace Majora\Component\OAuth\Repository\ORM;

use Majora\Component\OAuth\Model\RefreshTokenInterface;
use Majora\Component\OAuth\Repository\RefreshTokenRepositoryInterface;

/**
 * RefreshToken repository implementation.
 *
 * @see Majora\Component\OAuth\Repository\TokenRepositoryInterface
 */
class RefreshTokenRepository extends TokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * @see TokenRepositoryInterface::persist()
     */
    public function persist(RefreshTokenInterface $refreshToken)
    {
        $em = $this->getEntityManager();

        $em->persist($refreshToken);
        $em->flush();
    }

    /**
     * @see TokenRepositoryInterface::remove()
     */
    public function remove(RefreshTokenInterface $refreshToken)
    {
        $em = $this->getEntityManager();

        $em->remove($refreshToken);
        $em->flush();
    }
}
