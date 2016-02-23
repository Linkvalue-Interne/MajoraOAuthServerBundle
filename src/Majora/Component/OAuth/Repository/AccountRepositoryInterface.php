<?php

namespace Majora\Component\OAuth\Repository;

use Majora\Component\OAuth\Model\AccountInterface;

/**
 * Account storage behavior definition.
 */
interface AccountRepositoryInterface
{
    /**
     * Store given Account into persistence.
     *
     * @param AccountInterface $accessToken
     */
    public function persist(AccountInterface $accessToken);

    /**
     * Remove given Account from persistence.
     *
     * @param AccountInterface $accessToken
     */
    public function remove(AccountInterface $accessToken);
}
