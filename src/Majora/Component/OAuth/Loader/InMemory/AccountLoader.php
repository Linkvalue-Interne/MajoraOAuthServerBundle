<?php

namespace Majora\Component\OAuth\Loader\InMemory;

use Majora\Component\OAuth\Loader\AccountLoaderInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Majora\Framework\Loader\Bridge\InMemory\InMemoryLoaderTrait;

/**
 * ORM Account loading implementation.
 */
class AccountLoader implements AccountLoaderInterface
{
    use InMemoryLoaderTrait;

    /**
     * {@inheritdoc}
     */
    public function retrieveOnApplicationByUsername(ApplicationInterface $application, $username)
    {

    }
}
