<?php

namespace Majora\Component\OAuth\Loader\ORM;

use Majora\Component\OAuth\Loader\ApplicationLoaderInterface;
use Majora\Framework\Loader\Bridge\Doctrine\AbstractDoctrineLoader;
use Majora\Framework\Loader\Bridge\Doctrine\DoctrineLoaderTrait;

/**
 * ORM Application loading.
 */
class ApplicationLoader extends AbstractDoctrineLoader implements ApplicationLoaderInterface
{
    use DoctrineLoaderTrait;

    /**
     * @inheritdoc
     */
    public function retrieveByApiKeyAndSecret($apiKey, $secret)
    {
        return $this
            ->entityRepository
            ->createQueryBuilder('a')
            ->where('a.secret = :secret')
            ->andWhere('a.apiKey = :api_key')
            ->setParameter('secret', $secret)
            ->setParameter('api_key', $apiKey)
            ->getQuery()
            ->getOneOrNullResult();
    }
}