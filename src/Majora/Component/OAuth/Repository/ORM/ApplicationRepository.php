<?php

namespace Majora\Component\OAuth\Repository\ORM;

use Majora\Component\OAuth\Repository\ApplicationRepositoryInterface;
use Majora\Framework\Repository\Doctrine\BaseDoctrineRepository;
use Majora\Framework\Repository\Doctrine\DoctrineRepositoryTrait;

/**
 * Application repository
 */
class ApplicationRepository extends BaseDoctrineRepository implements ApplicationRepositoryInterface
{
    use DoctrineRepositoryTrait;
}