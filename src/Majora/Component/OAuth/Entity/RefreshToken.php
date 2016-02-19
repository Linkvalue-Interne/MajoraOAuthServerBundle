<?php

namespace Majora\Component\OAuth\Entity;

use Majora\Component\OAuth\Model\RefreshTokenInterface;
use Majora\Component\OAuth\Model\AccountInterface;
use Majora\Component\OAuth\Model\ApplicationInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

/**
 * Class RefreshToken is the default implementation of RefreshTokenInterface
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
class RefreshToken extends AbstractToken implements RefreshTokenInterface
{
}
