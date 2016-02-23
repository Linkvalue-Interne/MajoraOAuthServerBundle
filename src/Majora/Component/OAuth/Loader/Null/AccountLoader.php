<?php

namespace Majora\Component\OAuth\Loader\Null;

use Majora\Component\OAuth\Loader\AccountLoaderInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;

/**
 * Empty Account loading implementation.
 */
class AccountLoader implements AccountLoaderInterface
{
    /**
     * @see AccountLoaderInterface::retrieveOnApplicationByUsername()
     */
    public function retrieveOnApplicationByUsername(ApplicationInterface $application, $username)
    {
        return;
    }
}
