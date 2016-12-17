<?php

namespace Majora\Component\OAuth\Tests\Loader\ORM;

use Majora\Component\OAuth\Loader\ORM\ApplicationLoader;
use Majora\Component\OAuth\Repository\ORM\ApplicationRepository;
use Prophecy\Argument;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;

/**
 * Unit tests class for Majora\Component\OAuth\Loader\ORM\ApplicationLoader.
 *
 * @see \Majora\Component\OAuth\Loader\ORM\ApplicationLoader
 */
class ApplicationLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests __construct() method.
     */
    public function testConstructor()
    {
        $applicationRepository = new ApplicationRepository(
            DoctrineTestHelper::createTestEntityManager(),
            new ClassMetadata(ApplicationRepository::class)
        );

        $applicationLoader = new ApplicationLoader(
            $applicationRepository
        );

        $reflectedClass = new \ReflectionClass(ApplicationLoader::class);
        $property = $reflectedClass->getProperty('applicationRepository');
        $property->setAccessible(true);

        $this->assertEquals($property->getValue($applicationLoader), $applicationRepository);
    }

    /**
     * Tests retrieveByApiKeyAndSecretQueryBuilder() method.
     */
    public function testRetrieveByApiKeyAndSecretQueryBuilder()
    {
        $applicationRepository = new ApplicationRepository(
            DoctrineTestHelper::createTestEntityManager(),
            new ClassMetadata(ApplicationRepository::class)
        );

        $applicationLoader = new ApplicationLoader(
            $applicationRepository
        );

        $queryBuilder = $applicationLoader->retrieveByApiKeyAndSecretQueryBuilder(Argument::any(), Argument::any());

        $query = $queryBuilder->getQuery();

        $this->assertEquals(
            $query->getDQL(),
            sprintf('SELECT a FROM %s a WHERE a.secret = :secret AND a.apiKey = :api_key', ApplicationRepository::class)
        );
    }
}
