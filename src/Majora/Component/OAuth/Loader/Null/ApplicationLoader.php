<?php

namespace Majora\Component\OAuth\Loader\Null;

use Majora\Component\OAuth\Loader\ApplicationLoaderInterface;

/**
 * Empty Application loading implementation.
 */
class ApplicationLoader implements ApplicationLoaderInterface
{
    /**
     * @see ApplicationLoaderInterface::retrieveByApiKeyAndSecret()
     */
    public function retrieveByApiKeyAndSecret($apiKey, $secret)
    {
        return;
    }
}
