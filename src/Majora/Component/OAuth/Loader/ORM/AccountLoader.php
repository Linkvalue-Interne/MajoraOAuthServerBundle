<?php

namespace Majora\Component\OAuth\Loader\ORM;

use Majora\Component\OAuth\Loader\AccountLoaderInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Majora\Component\OAuth\Repository\ORM\AccountRepository;

/**
 * ORM Account loading implementation.
 */
class AccountLoader implements AccountLoaderInterface
{
    /**
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * Construct.
     *
     * @param AccountRepository $accountRepository
     */
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveOnApplicationByUsername(ApplicationInterface $application, $username)
    {
        return $this->accountRepository
            ->createQueryBuilder('ac')
                ->innerJoin('ac.applications', 'app')
                ->where('ac.username = :username')
                    ->setParameter('username', $username)
                ->andWhere('app.id = :application')
                    ->setParameter('application', $application->getId())
            ->getQuery()
                ->getOneOrNullResult()
        ;
    }
}
