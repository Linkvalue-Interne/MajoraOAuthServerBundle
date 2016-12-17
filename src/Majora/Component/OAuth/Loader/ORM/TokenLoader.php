<?php

namespace Majora\Component\OAuth\Loader\ORM;

use Majora\Component\OAuth\Loader\TokenLoaderInterface;
use Majora\Component\OAuth\Repository\ORM\TokenRepository;
use Majora\Framework\Date\Clock;

/**
 * ORM token loading implementation.
 */
class TokenLoader implements TokenLoaderInterface
{
    /**
     * @var TokenRepository
     */
    protected $tokenRepository;

    /**
     * @var Clock
     */
    protected $clock;

    /**
     * Construct.
     *
     * @param TokenRepository $tokenRepository
     * @param Clock           $clock
     */
    public function __construct(TokenRepository $tokenRepository, Clock $clock)
    {
        $this->tokenRepository = $tokenRepository;
        $this->clock = $clock;
    }

    /**
     * @see TokenLoaderInterface::retrieveByHash()
     */
    public function retrieveByHash($hash)
    {
        return $this->retrieveByHashQueryBuilder($hash)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    /**
     * @param $hash
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function retrieveByHashQueryBuilder($hash)
    {
        return $this->tokenRepository
            ->createQueryBuilder('t')
            ->where('t.hash = :hash')
            ->setParameter('hash', $hash)
            ->andWhere('t.expireAt > :now')
            ->setParameter('now', $this->clock->now());
    }

    /**
     * @see TokenLoaderInterface::retrieveExpired()
     */
    public function retrieveExpired(\DateTime $datetime = null)
    {
        return $this->retrieveExpiredQueryBuilder($datetime)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param \DateTime|null $datetime
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function retrieveExpiredQueryBuilder(\DateTime $datetime = null)
    {
        return $this->tokenRepository
            ->createQueryBuilder('t')
            ->where('t.expireAt < :date')
            ->setParameter('date', $datetime ?: $this->clock->now());
    }
}
