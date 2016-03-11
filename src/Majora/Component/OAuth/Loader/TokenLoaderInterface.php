<?php

namespace Majora\Component\OAuth\Loader;

/**
 * Token fetching behavior definition.
 */
interface TokenLoaderInterface
{
    /**
     * Retrieves a token by its hash.
     *
     * @param string $hash
     *
     * @return TokenInterface|null
     */
    public function retrieveByHash($hash);

    /**
     * Retrieve expires token at given date.
     *
     * @param \DateTime $datetime
     *
     * @return TokenInterface[]
     */
    public function retrieveExpired(\DateTime $datetime = null);
}
