<?php

namespace Majora\Component\OAuth\Model;

/**
 * Interface to implement on entities used as Token.
 */
interface TokenInterface
{
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
     * Returns token expiration date.
     *
     * @return \DateTime
     */
    public function getExpireAt();

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
