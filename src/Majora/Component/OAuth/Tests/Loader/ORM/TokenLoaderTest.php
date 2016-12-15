<?php

namespace Majora\Component\OAuth\Tests\Loader\ORM;

use Majora\Component\OAuth\Entity\Token;
use Majora\Component\OAuth\Loader\ORM\TokenLoader;
use Majora\Component\OAuth\Repository\ORM\TokenRepository;
use Majora\Framework\Date\Clock;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Unit tests class for Majora\Component\OAuth\Loader\ORM\TokenLoader.
 *
 * @see \Majora\Component\OAuth\Loader\ORM\TokenLoader
 */
abstract class TokenLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Majora\Framework\Date\Clock
     */
    protected $clock;

    /**
     * @var \Majora\Component\OAuth\Loader\TokenLoaderInterface
     */
    protected $tokenLoader;

    /**
     * @var \Majora\Component\OAuth\Repository\TokenRepositoryInterface
     */
    protected $tokenRepository;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->clock = new Clock();

        $this->tokenRepository = new TokenRepository(
            DoctrineTestHelper::createTestEntityManager(),
            new ClassMetadata(TokenRepository::class)
        );

        $this->tokenLoader = new TokenLoader(
            $this->tokenRepository,
            $this->clock
        );
    }

    /**
     * Tests __construct() method.
     */
    public function testConstructor()
    {
        $tokenRepositoryClass = $this->tokenRepository->getClassName();
        $tokenLoaderClass = get_class($this->tokenLoader);

        $tokenRepository = new $tokenRepositoryClass(
            DoctrineTestHelper::createTestEntityManager(),
            new ClassMetadata($tokenRepositoryClass)
        );

        $tokenLoader = new $tokenLoaderClass(
            $tokenRepository,
            $this->clock
        );

        $reflectedClass = new \ReflectionClass($tokenLoaderClass);
        $property = $reflectedClass->getProperty('tokenRepository');
        $property->setAccessible(true);

        $this->assertEquals($property->getValue($tokenLoader), $tokenRepository);
    }

    /**
     * Tests retrieveByHashQueryBuilder() method.
     */
    public function testRetrieveByHashQueryBuilder()
    {
        $queryBuilder = $this->tokenLoader->retrieveByHashQueryBuilder('1234');

        $query = $queryBuilder->getQuery();

        $this->assertEquals(
            $query->getDQL(),
            sprintf(
                'SELECT t FROM %s t WHERE t.hash = :hash AND t.expireAt > :now',
                $this->tokenRepository->getClassName()
            )
        );
    }

    /**
     * Tests retrieveExpiredQueryBuilder() method.
     */
    public function testRetrieveExpiredQueryBuilder()
    {
        $queryBuilder = $this->tokenLoader->retrieveExpiredQueryBuilder($this->clock->now());

        $query = $queryBuilder->getQuery();

        $this->assertEquals(
            $query->getDQL(),
            sprintf('SELECT t FROM %s t WHERE t.expireAt < :date', $this->tokenRepository->getClassName())
        );
    }
}
