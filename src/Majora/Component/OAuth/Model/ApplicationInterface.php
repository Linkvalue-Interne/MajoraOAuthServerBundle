<?php

namespace Majora\Component\OAuth\Model;

/**
 * Interface to implement on client application.
 */
interface ApplicationInterface
{
    /**
     * Returns application Api key.
     *
     * @return string
     */
    public function getApiKey();

    /**
     * Returns application secret.
     *
     * @return string
     */
    public function getSecret();

    /**
     * Returns application domain.
     *
     * @return string
     */
    public function getDomain();

    /**
     * Return application allowed scopes.
     *
     * @return array
     */
    public function getAllowedScopes();

    /**
     * Return application allowed grant types.
     *
     * @return array
     */
    public function getAllowedGrantTypes();

    /**
     * Return application roles.
     *
     * @return array
     */
    public function getRoles();
}
