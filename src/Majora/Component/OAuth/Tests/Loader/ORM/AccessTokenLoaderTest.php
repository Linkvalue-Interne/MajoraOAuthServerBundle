<?php

namespace Majora\Component\OAuth\Tests\Loader\ORM;

use Majora\Component\OAuth\Entity\AccessToken;
use Majora\Component\OAuth\Loader\ORM\AccessTokenLoader;
use Majora\Component\OAuth\Repository\ORM\AccessTokenRepository;
use Majora\Framework\Date\Clock;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Unit tests class for Majora\Component\OAuth\Loader\ORM\AccessTokenLoader.
 *
 * @see \Majora\Component\OAuth\Loader\ORM\AccessTokenLoader
 */
class AccessTokenLoaderTest extends TokenLoaderTest
{
    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->clock = new Clock();

        $this->tokenRepository = new AccessTokenRepository(
            DoctrineTestHelper::createTestEntityManager(),
            new ClassMetadata(AccessTokenRepository::class)
        );

        $this->tokenLoader = new AccessTokenLoader(
            $this->tokenRepository,
            $this->clock
        );
    }
}
