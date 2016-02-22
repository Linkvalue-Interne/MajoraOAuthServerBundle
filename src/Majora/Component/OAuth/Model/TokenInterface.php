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
     * Returns token as string.
     *
     * @return string
     */
    public function __toString();

    /**
     * Returns token hash as string.
     *
     * @return string
     */
    public function getHash();

    /**
     * Returns token ttl in sec.
     *
     * @return int
     */
    public function getExpireIn();

    /**
     * Returns token related account.
     *
     * @return AccountInterface|null
     */
    public function getAccount();

    /**
     * Returns token related client.
     *
     * @return ApplicationInterface
     */
    public function getApplication();

    /**
     * Returns token roles, compiled from Application and Account roles.
     *
     * @return array
     */
    public function getRoles();
}
