<?php

namespace Majora\Component\OAuth\Loader;

/**
 * Interface TokenLoaderInterface
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
interface TokenLoaderInterface
{
    /**
     * Retrieves a AccessToken by its hash
     *
     * @param string $hash
     * @return TokenLoaderInterface|null
     */
    public function retrieveByHash($hash);
}