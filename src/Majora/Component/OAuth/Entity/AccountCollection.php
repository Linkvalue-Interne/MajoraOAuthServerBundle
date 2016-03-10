<?php

namespace Majora\Component\OAuth\Entity;

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
