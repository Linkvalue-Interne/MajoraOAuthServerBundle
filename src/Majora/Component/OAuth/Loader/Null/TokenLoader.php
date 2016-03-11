<?php

namespace Majora\Component\OAuth\Loader\Null;

use Majora\Component\OAuth\Loader\TokenLoaderInterface;

/**
 * Empty token loading implementation.
 */
class TokenLoader implements TokenLoaderInterface
{
    /**
     * @see TokenLoaderInterface::retrieveByHash()
     */
    public function retrieveByHash($hash)
    {
        return;
    }

    /**
     * @see TokenLoaderInterface::retrieveExpired()
     */
    public function retrieveExpired(\DateTime $datetime = null)
    {
        return array();
    }
}
