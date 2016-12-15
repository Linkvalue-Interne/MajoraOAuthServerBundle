<?php

namespace Majora\Component\OAuth\Repository\Loader\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Majora\Component\OAuth\Entity\RefreshToken;
use Majora\Component\OAuth\Repository\ORM\RefreshTokenRepository;

/**
 * Unit tests class for Majora\Component\OAuth\Repository\ORM\RefreshTokenRepository.
 *
 * @see Majora\Component\OAuth\Repository\ORM\RefreshTokenRepository
 */
class RefreshTokenRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests persist() method.
     */
    public function testPersist()
    {
        $token = $this->prophesize(RefreshToken::class)->reveal();
        $em = $this->prophesize(EntityManagerInterface::class);

        $em->persist($token)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $repository = new RefreshTokenRepository(
            $em->reveal(),
            new ClassMetadata(RefreshToken::class)
        );

        $repository->persist($token);
    }

    /**
     * Tests remove() method.
     */
    public function testRemove()
    {
        $token = $this->prophesize(RefreshToken::class)->reveal();
        $em = $this->prophesize(EntityManagerInterface::class);

        $em->remove($token)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $repository = new RefreshTokenRepository(
            $em->reveal(),
            new ClassMetadata(RefreshToken::class)
        );

        $repository->remove($token);
    }
}
