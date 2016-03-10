<?php

namespace Majora\Component\OAuth\Entity;

use Majora\Framework\Model\EntityCollection;

/**
 * Access token model collection class.
 */
class AccessTokenCollection extends EntityCollection
{
    /**
     * @see Majora\Framework\Model\EntityCollection::getEntityClass()
     */
    protected function getEntityClass()
    {
        return AccessToken::class;
    }
}
