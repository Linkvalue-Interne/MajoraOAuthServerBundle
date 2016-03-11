<?php

namespace Majora\Component\OAuth\Loader;

use Majora\Component\OAuth\Model\ApplicationInterface;

/**
 * Account fetching behavior definition.
 */
interface AccountLoaderInterface
{
    /**
     * Loads an Account on given app, based on given username.
     *
     * @param ApplicationInterface $application
     * @param string               $username
     *
     * @return Account|null
     */
    public function retrieveOnApplicationByUsername(ApplicationInterface $application, $username);
}
