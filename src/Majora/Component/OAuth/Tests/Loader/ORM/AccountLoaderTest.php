<?php

namespace Majora\Component\OAuth\Tests\Loader\ORM;

use Majora\Component\OAuth\Loader\ORM\AccountLoader;
use Majora\Component\OAuth\Repository\ORM\AccountRepository;
use Prophecy\Argument;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;

/**
 * Unit tests class for Majora\Component\OAuth\Loader\ORM\AccountLoader.
 *
 * @see \Majora\Component\OAuth\Loader\ORM\AccountLoader
 */
class AccountLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests __construct() method.
     */
    public function testConstructor()
    {
        $accountRepository = new AccountRepository(
            DoctrineTestHelper::createTestEntityManager(),
            new ClassMetadata(AccountRepository::class)
        );

        $accountLoader = new AccountLoader(
            $accountRepository
        );

        $reflectedClass = new \ReflectionClass(AccountLoader::class);
        $property = $reflectedClass->getProperty('accountRepository');
        $property->setAccessible(true);

        $this->assertEquals($property->getValue($accountLoader), $accountRepository);
    }

    /**
     * Tests retrieveOnApplicationByUsernameQueryBuilder() method.
     */
    public function testRetrieveOnApplicationByUsernameQueryBuilder()
    {
        $accountRepository = new AccountRepository(
            DoctrineTestHelper::createTestEntityManager(),
            new ClassMetadata(AccountRepository::class)
        );

        $accountLoader = new AccountLoader(
            $accountRepository
        );

        $application = $this->prophesize('Majora\Component\OAuth\Entity\Application');

        $queryBuilder = $accountLoader->retrieveOnApplicationByUsernameQueryBuilder(
            $application->reveal(),
            Argument::any()
        );

        $query = $queryBuilder->getQuery();

        $this->assertEquals(
            $query->getDQL(),
            sprintf(
                'SELECT ac FROM %s ac INNER JOIN ac.applications app WHERE ac.username = :username AND app.id = :application',
                AccountRepository::class
            )
        );
    }
}
