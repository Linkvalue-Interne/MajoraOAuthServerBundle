<?php

namespace Majora\Component\OAuth\Collection;

use Majora\Component\OAuth\Entity\Application;
use Majora\Framework\Model\EntityCollection;

/**
 * Application model collection class.
 */
class ApplicationCollection extends EntityCollection
{
    /**
     * @see Majora\Framework\Model\EntityCollection::getEntityClass()
     */
    protected function getEntityClass()
    {
        return Application::class;
    }
}