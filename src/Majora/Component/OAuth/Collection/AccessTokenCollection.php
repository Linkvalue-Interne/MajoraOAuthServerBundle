<?php

namespace Majora\Component\OAuth\Collection;

use Majora\Component\OAuth\Entity\AccessToken;
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