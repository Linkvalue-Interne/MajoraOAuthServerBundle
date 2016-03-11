<?php

namespace Majora\Component\OAuth\Repository\ORM;

use Majora\Component\OAuth\Model\AccountInterface;
use Majora\Component\OAuth\Repository\AccountRepositoryInterface;
use Majora\Framework\Repository\Doctrine\BaseDoctrineRepository;

/**
 * Account repository implementation.
 */
class AccountRepository extends BaseDoctrineRepository implements AccountRepositoryInterface
{
    /**
     * @see AccountRepositoryInterface::persist()
     */
    public function persist(AccountInterface $account)
    {
        $em = $this->getEntityManager();

        $em->persist($account);
        $em->flush();
    }

    /**
     * @see AccountRepositoryInterface::remove()
     */
    public function remove(AccountInterface $account)
    {
        $em = $this->getEntityManager();

        $em->remove($account);
        $em->flush();
    }
}
