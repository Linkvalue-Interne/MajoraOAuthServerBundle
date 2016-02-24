<?php

namespace Majora\Component\OAuth\Loader\ORM;

use Majora\Component\OAuth\Loader\TokenLoaderInterface;
use Majora\Component\OAuth\Repository\ORM\TokenRepository;

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
     * Construct.
     *
     * @param TokenRepository $tokenRepository
     */
    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
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
            ->getQuery()
                ->getOneOrNullResult()
        ;
    }

    /**
     * @see TokenLoaderInterface::retrieveExpired()
     */
    public function retrieveExpired(\DateTime $datetime)
    {
        return $this->tokenRepository
            ->createQueryBuilder('t')
                ->where('t.expireAt > :date')
                ->setParameter('date', $datetime)
            ->getQuery()
                ->getResult()
        ;
    }
}
