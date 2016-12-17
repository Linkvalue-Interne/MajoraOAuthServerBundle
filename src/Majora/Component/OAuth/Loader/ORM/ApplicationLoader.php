<?php

namespace Majora\Component\OAuth\Loader\ORM;

use Majora\Component\OAuth\Loader\ApplicationLoaderInterface;
use Majora\Component\OAuth\Repository\ORM\ApplicationRepository;

/**
 * ORM Application loading implementation.
 */
class ApplicationLoader implements ApplicationLoaderInterface
{
    /**
     * @var ApplicationRepository
     */
    protected $applicationRepository;

    /**
     * Constructor.
     *
     * @param ApplicationRepository $applicationRepository
     */
    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveByApiKeyAndSecret($apiKey, $secret)
    {
        return $this->retrieveByApiKeyAndSecretQueryBuilder($apiKey, $secret)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $apiKey
     * @param $secret
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function retrieveByApiKeyAndSecretQueryBuilder($apiKey, $secret)
    {
        return $this->applicationRepository
            ->createQueryBuilder('a')
            ->where('a.secret = :secret')
            ->setParameter('secret', $secret)
            ->andWhere('a.apiKey = :api_key')
            ->setParameter('api_key', $apiKey);
    }
}
