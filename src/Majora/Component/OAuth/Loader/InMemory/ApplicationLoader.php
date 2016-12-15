<?php

namespace Majora\Component\OAuth\Loader\InMemory;

use Majora\Component\OAuth\Loader\ApplicationLoaderInterface;
use Majora\Framework\Loader\Bridge\InMemory\InMemoryLoaderTrait;

/**
 * ORM Application loading implementation.
 */
class ApplicationLoader implements ApplicationLoaderInterface
{
    use InMemoryLoaderTrait;

    /**
     * {@inheritdoc}
     */
    public function retrieveByApiKeyAndSecret($apiKey, $secret)
    {

    }
}
