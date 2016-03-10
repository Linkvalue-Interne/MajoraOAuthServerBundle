<?php

namespace Majora\Component\OAuth\Entity;

use Majora\Component\OAuth\Model\AccountInterface;
use Majora\Framework\Model\CollectionableInterface;
use Majora\Framework\Model\CollectionableTrait;
use Majora\Framework\Serializer\Model\SerializableTrait;

/**
 * Basic implementation on AccountInterface.
 */
class Account implements AccountInterface, CollectionableInterface
{
    use CollectionableTrait, SerializableTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $ownerId;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $salt;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var array
     */
    protected $applications;

    /**
     * @see NormalizableInterface::getScope()
     */
    public static function getScopes()
    {
        return array(
            'id' => 'id',
            'default' => array('id', 'owner_id', 'username'),
        );
    }

    /**
     * Construct.
     *
     * @param string $rand
     */
    public function __construct($rand = 'm@J0raOaUth')
    {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true).$rand), 16, 36);
        $this->roles = array();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @see AccountInterface::getOwnerId()
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * Define Account ownerId.
     *
     * @param int $ownerId
     *
     * @return self
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;

        return $this;
    }

    /**
     * @see UserInterface::getUsername()
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * define Account username.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface::getPassword()
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Define Account password.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface::getSalt()
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @see UserInterface::getRoles()
     *
     * @codeCoverageIgnore
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @see UserInterface::eraseCredentials()
     *
     * @codeCoverageIgnore
     */
    public function eraseCredentials()
    {
        return;
    }

    /**
     * @return array
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * @param array $applications
     */
    public function setApplications($applications)
    {
        $this->applications = $applications;
    }
}
