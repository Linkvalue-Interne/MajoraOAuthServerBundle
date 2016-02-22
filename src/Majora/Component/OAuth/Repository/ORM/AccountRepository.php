<?php

namespace Majora\Component\OAuth\Repository\ORM;

use Majora\Component\OAuth\Repository\AccountRepositoryInterface;
use Majora\Framework\Repository\Doctrine\BaseDoctrineRepository;
use Majora\Framework\Repository\Doctrine\DoctrineRepositoryTrait;

/**
 * Account repository
 */
class AccountRepository extends BaseDoctrineRepository implements AccountRepositoryInterface
{
    use DoctrineRepositoryTrait;
}