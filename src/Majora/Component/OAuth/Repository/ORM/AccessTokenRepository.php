<?php

namespace Majora\Component\OAuth\Repository\ORM;

use Majora\Component\OAuth\Model\AccessTokenInterface;
use Majora\Component\OAuth\Repository\AccessTokenRepositoryInterface;

/**
 * AccessToken repository implementation.
 *
 * @see Majora\Component\OAuth\Repository\TokenRepositoryInterface
 */
class AccessTokenRepository extends TokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * @see TokenRepositoryInterface::persist()
     */
    public function persist(AccessTokenInterface $accessToken)
    {
        $em = $this->getEntityManager();

        $em->persist($accessToken);
        $em->flush();
    }

    /**
     * @see TokenRepositoryInterface::remove()
     */
    public function remove(AccessTokenInterface $accessToken)
    {
        $em = $this->getEntityManager();

        $em->remove($accessToken);
        $em->flush();
    }
}
