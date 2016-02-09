<?php

namespace Majora\Component\OAuth\Loader;

use Majora\Component\OAuth\Model\ApplicationInterface;

/**
 * Behavior for Account loading.
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
