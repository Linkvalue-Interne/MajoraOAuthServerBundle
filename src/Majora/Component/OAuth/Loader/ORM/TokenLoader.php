<?php

namespace Majora\Component\OAuth\Loader\ORM;

use Majora\Component\OAuth\Loader\TokenLoaderInterface;
use Majora\Framework\Loader\Bridge\Doctrine\AbstractDoctrineLoader;
use Majora\Framework\Loader\Bridge\Doctrine\DoctrineLoaderTrait;

/**
 * ORM token loading.
 */
class TokenLoader extends AbstractDoctrineLoader implements TokenLoaderInterface
{
    use DoctrineLoaderTrait;

    /**
     * @inheritdoc
     */
    public function retrieveByHash($hash)
    {
        return $this
            ->entityRepository
            ->createQueryBuilder('t')
            ->where('t.hash = :hash')
            ->setParameter('hash', $hash)
            ->getQuery()
            ->getOneOrNullResult();
    }
}