<?php

namespace Majora\Component\OAuth\Repository\ORM;

use Majora\Component\OAuth\Repository\AccessTokenRepositoryInterface;
use Majora\Framework\Repository\Doctrine\BaseDoctrineRepository;
use Majora\Framework\Repository\Doctrine\DoctrineRepositoryTrait;

/**
 * Access token repository
 */
class AccessTokenRepository extends BaseDoctrineRepository implements AccessTokenRepositoryInterface
{
    use DoctrineRepositoryTrait;
}