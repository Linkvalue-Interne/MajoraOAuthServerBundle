<?php

namespace Majora\Component\OAuth\Entity;

use Majora\Component\OAuth\Model\RefreshTokenInterface;
use Majora\Framework\Model\CollectionableInterface;
use Majora\Framework\Model\CollectionableTrait;
use Majora\Framework\Normalizer\Model\NormalizableTrait;

/**
 * Class RefreshToken is the default implementation of RefreshTokenInterface.
 */
class RefreshToken extends Token implements RefreshTokenInterface, CollectionableInterface
{
    use CollectionableTrait, NormalizableTrait;

    /**
     * {@inheritdoc}
     */
    public static function getScopes()
    {
        return array(
            'identifier' => array('id', 'hash'),
        );
    }
}
