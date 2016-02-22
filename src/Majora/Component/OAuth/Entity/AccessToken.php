<?php

namespace Majora\Component\OAuth\Entity;

use Majora\Component\OAuth\Model\AccessTokenInterface;
use Majora\Component\OAuth\Model\AccountInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Majora\Component\OAuth\Model\RefreshTokenInterface;
use Majora\Framework\Model\CollectionableInterface;
use Majora\Framework\Model\CollectionableTrait;
use Majora\Framework\Serializer\Model\SerializableTrait;

/**
 * Access token class.
 */
class AccessToken extends Token implements AccessTokenInterface, CollectionableInterface
{
    use CollectionableTrait, SerializableTrait;

    /**
     * @var RefreshTokenInterface
     */
    protected $refreshToken;

    /**
     * @see AccessTokenInterface::__construct()
     */
    public function __construct(
        ApplicationInterface $application,
        AccountInterface $account = null,
        $expireIn = AccessTokenInterface::DEFAULT_TTL,
        $hash = null,
        RefreshTokenInterface $refreshToken = null
    ) {
        parent::__construct($application, $account, $expireIn, $hash);
        $this->refreshToken = $refreshToken;
    }

    /**
     * @see AccessTokenInterface::getRefreshToken()
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @inheritdoc
     */
    public static function getScopes()
    {
        return array(
            'identifier' => array('id', 'hash'),
        );
    }
}
