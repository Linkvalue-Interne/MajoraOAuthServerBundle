<?php

namespace Majora\Component\OAuth\Entity;

use Majora\Framework\Model\EntityCollection;

/**
 * Application model collection class.
 */
class ApplicationCollection extends EntityCollection
{
    /**
     * @see Majora\Framework\Model\EntityCollection::getEntityClass()
     */
    protected static function getEntityClass()
    {
        return Application::class;
    }
}
