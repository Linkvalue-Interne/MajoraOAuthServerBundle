<?php
namespace Majora\Component\OAuth\Loader;

use Majora\Component\OAuth\Model\RefreshTokenInterface;

/**
 * Interface RefreshTokenLoaderInterface
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
interface RefreshTokenLoaderInterface
{
    /**
     * Retrieves a RefreshToken by its hash
     *
     * @param string $hash
     * @return RefreshTokenInterface|null
     */
    public function retrieveByHash($hash);
}