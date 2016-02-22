<?php

namespace Majora\Component\OAuth\Collection;

use Majora\Component\OAuth\Entity\Account;
use Majora\Framework\Model\EntityCollection;

/**
 * Account model collection class.
 */
class AccountCollection extends EntityCollection
{
    /**
     * @see Majora\Framework\Model\EntityCollection::getEntityClass()
     */
    protected function getEntityClass()
    {
        return Account::class;
    }
}