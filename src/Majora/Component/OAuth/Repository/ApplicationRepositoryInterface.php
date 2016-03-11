<?php

namespace Majora\Component\OAuth\Repository;

use Majora\Component\OAuth\Model\ApplicationInterface;

/**
 * Application storage behavior definition.
 */
interface ApplicationRepositoryInterface
{
    /**
     * Store given Application into persistence.
     *
     * @param ApplicationInterface $accessToken
     */
    public function persist(ApplicationInterface $accessToken);

    /**
     * Remove given Application from persistence.
     *
     * @param ApplicationInterface $accessToken
     */
    public function remove(ApplicationInterface $accessToken);
}
