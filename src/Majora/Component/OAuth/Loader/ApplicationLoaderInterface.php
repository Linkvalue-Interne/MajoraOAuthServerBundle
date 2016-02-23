<?php

namespace Majora\Component\OAuth\Loader;

/**
 * Application fetching behavior definition.
 */
interface ApplicationLoaderInterface
{
    /**
     * Have to fetch and return an Application for given api key and secret couple.
     *
     * @param string $apiKey
     * @param string $secret
     *
     * @return ApplicationInterface|null
     */
    public function retrieveByApiKeyAndSecret($apiKey, $secret);
}
