<?php

namespace Majora\Component\OAuth\Repository\Null;

use Majora\Component\OAuth\Model\AccountInterface;
use Majora\Component\OAuth\Repository\AccountRepositoryInterface;

/**
 * Account repository implementation.
 */
class AccountRepository implements AccountRepositoryInterface
{
    /**
     * @see AccountRepositoryInterface::persist()
     */
    public function persist(AccountInterface $account)
    {
    }

    /**
     * @see AccountRepositoryInterface::remove()
     */
    public function remove(AccountInterface $account)
    {
    }
}
