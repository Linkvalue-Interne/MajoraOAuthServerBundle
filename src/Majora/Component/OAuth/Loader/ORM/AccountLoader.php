<?php

namespace Majora\Component\OAuth\Loader\ORM;

use Majora\Component\OAuth\Loader\AccountLoaderInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Majora\Framework\Loader\Bridge\Doctrine\AbstractDoctrineLoader;
use Majora\Framework\Loader\Bridge\Doctrine\DoctrineLoaderTrait;

/**
 * ORM Account loading.
 */
class AccountLoader extends AbstractDoctrineLoader implements AccountLoaderInterface
{
    use DoctrineLoaderTrait;

    /**
     * @inheritdoc
     */
    public function retrieveOnApplicationByUsername(ApplicationInterface $application, $username)
    {
        return $this->entityRepository
                    ->createQueryBuilder('ac')
                    ->innerJoin('ac.applications', 'app')
                    ->where('ac.username = :username')
                    ->andWhere('app.id = :application')
                    ->setParameter('application', $application->getId())
                    ->setParameter('username', $username)
                    ->getQuery()
                    ->getOneOrNullResult();
    }
}