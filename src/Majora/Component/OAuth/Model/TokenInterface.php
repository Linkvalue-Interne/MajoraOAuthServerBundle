<?php

namespace Majora\Component\OAuth\Model;

/**
 * Interface to implement on entities used as Token.
 */
interface TokenInterface
{
    /**
     * Construction function.
     *
     * @param ApplicationInterface $application
     * @param AccountInterface     $account
     * @param int                  $expireIn
     * @param string|null          $hash
     */
    public function __construct(
        ApplicationInterface $application,
        AccountInterface $account = null,
        $expireIn = 0,
        $hash = null
    );

    /**
     * Returns access token hash as string.
     *
     * @return string
     */
    public function getHash();

    /**
     * Returns access token ttl in sec.
     *
     * @return int
     */
    public function getExpireIn();

    /**
     * Returns access token related account.
     *
     * @return AccountInterface|null
     */
    public function getAccount();

    /**
     * Returns access token related client.
     *
     * @return ApplicationInterface
     */
    public function getApplication();

    /**
     * Returns access token roles, compiled from Application and Account roles.
     *
     * @return array
     */
    public function getRoles();
}
