<?php

namespace Majora\Component\OAuth\Entity;

use Majora\Component\OAuth\Model\ApplicationInterface;

/**
 * Basic implementation on ApplicationInterface.
 */
class Application implements ApplicationInterface
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var array
     */
    protected $allowedScopes;

    /**
     * @var array
     */
    protected $allowedGrantTypes;

    /**
     * @var array
     */
    protected $roles;

    /**
     * Construct.
     */
    public function __construct()
    {
        $this->allowedScopes = array();
        $this->allowedGrantTypes = array();
        $this->roles = array();
    }

    /**
     * @see ApplicationInterface::getApiKey()
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Define Application apiKey value.
     *
     * @param string $apiKey
     *
     * @return self
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @see ApplicationInterface::getSecret()
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Define Application secret value.
     *
     * @param string $secret
     *
     * @return self
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @see ApplicationInterface::getDomain()
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Define Application domain value.
     *
     * @param string $domain
     *
     * @return self
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @see ApplicationInterface::getAllowedScopes()
     */
    public function getAllowedScopes()
    {
        return $this->allowedScopes;
    }

    /**
     * Define Application allowedScopes value.
     *
     * @param array $allowedScopes
     *
     * @return self
     */
    public function setAllowedScopes(array $allowedScopes)
    {
        $this->allowedScopes = $allowedScopes;

        return $this;
    }

    /**
     * @see ApplicationInterface::getAllowedGrantTypes()
     */
    public function getAllowedGrantTypes()
    {
        return $this->allowedGrantTypes;
    }

    /**
     * Define Application allowedGrantTypes value.
     *
     * @param array $allowedGrantTypes
     *
     * @return self
     */
    public function setAllowedGrantTypes(array $allowedGrantTypes)
    {
        $this->allowedGrantTypes = $allowedGrantTypes;

        return $this;
    }

    /**
     * @see ApplicationInterface::getRoles()
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Define Application roles value.
     *
     * @param array $roles
     *
     * @return self
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }
}
