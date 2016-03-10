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
        return $this->tokenRepository
            ->createQueryBuilder('t')
                ->where('t.hash = :hash')
                    ->setParameter('hash', $hash)
                ->andWhere('t.expireAt > :now')
                    ->setParameter('now', $this->clock->now())
            ->getQuery()
                ->setMaxResults(1)
                ->getOneOrNullResult()
        ;
    }

    /**
     * @see TokenLoaderInterface::retrieveExpired()
     */
    public function retrieveExpired(\DateTime $datetime = null)
    {
        return $this->tokenRepository
            ->createQueryBuilder('t')
                ->where('t.expireAt < :date')
                ->setParameter('date', $datetime ?: $this->clock->now())
            ->getQuery()
                ->getResult()
        ;
    }
}
