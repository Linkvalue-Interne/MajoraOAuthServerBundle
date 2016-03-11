<?php

namespace Majora\Component\OAuth\Repository\Null;

use Majora\Component\OAuth\Model\ApplicationInterface;
use Majora\Component\OAuth\Repository\ApplicationRepositoryInterface;

/**
 * Application repository empty implementation.
 */
class ApplicationRepository implements ApplicationRepositoryInterface
{
    /**
     * @see TokenRepositoryInterface::persist()
     */
    public function persist(ApplicationInterface $application)
    {
    }

    /**
     * @see TokenRepositoryInterface::remove()
     */
    public function remove(ApplicationInterface $application)
    {
    }
}
