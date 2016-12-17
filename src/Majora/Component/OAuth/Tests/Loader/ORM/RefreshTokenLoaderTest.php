<?php

namespace Majora\Component\OAuth\Tests\Loader\ORM;

use Majora\Component\OAuth\Entity\RefreshToken;
use Majora\Component\OAuth\Loader\ORM\RefreshTokenLoader;
use Majora\Component\OAuth\Repository\ORM\RefreshTokenRepository;
use Majora\Framework\Date\Clock;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Unit tests class for Majora\Component\OAuth\Loader\ORM\RefreshTokenLoader.
 *
 * @see \Majora\Component\OAuth\Loader\ORM\RefreshTokenLoader
 */
class RefreshTokenLoaderTest extends TokenLoaderTest
{
    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->clock = new Clock();

        $this->tokenRepository = new RefreshTokenRepository(
            DoctrineTestHelper::createTestEntityManager(),
            new ClassMetadata(RefreshTokenRepository::class)
        );

        $this->tokenLoader = new RefreshTokenLoader(
            $this->tokenRepository,
            $this->clock
        );
    }
}
