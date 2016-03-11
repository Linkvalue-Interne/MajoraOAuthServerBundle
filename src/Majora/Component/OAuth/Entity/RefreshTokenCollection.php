<?php

namespace Majora\Component\OAuth\Entity;

use Majora\Framework\Model\EntityCollection;

/**
 * Refresh token model collection class.
 */
class RefreshTokenCollection extends EntityCollection
{
    /**
     * @see Majora\Framework\Model\EntityCollection::getEntityClass()
     */
    protected function getEntityClass()
    {
        return RefreshToken::class;
    }
}
