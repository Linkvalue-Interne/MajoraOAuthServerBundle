<?php

namespace Majora\Component\OAuth\Repository\ORM;

use Majora\Component\OAuth\Repository\RefreshTokenRepositoryInterface;
use Majora\Framework\Repository\Doctrine\BaseDoctrineRepository;
use Majora\Framework\Repository\Doctrine\DoctrineRepositoryTrait;

/**
 * Refresh token repository
 */
class RefreshTokenRepository extends BaseDoctrineRepository implements RefreshTokenRepositoryInterface
{
    use DoctrineRepositoryTrait;
}