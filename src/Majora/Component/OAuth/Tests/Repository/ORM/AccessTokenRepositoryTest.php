<?php

namespace Majora\Component\OAuth\Repository\Loader\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Majora\Component\OAuth\Entity\AccessToken;
use Majora\Component\OAuth\Repository\ORM\AccessTokenRepository;

/**
 * Unit tests class for Majora\Component\OAuth\Repository\ORM\AccessTokenRepository.
 *
 * @see Majora\Component\OAuth\Repository\ORM\AccessTokenRepository
 */
class AccessTokenRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests persist() method.
     */
    public function testPersist()
    {
        $token = $this->prophesize(AccessToken::class)->reveal();
        $em = $this->prophesize(EntityManagerInterface::class);

        $em->persist($token)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $repository = new AccessTokenRepository(
            $em->reveal(),
            new ClassMetadata(AccessToken::class)
        );

        $repository->persist($token);
    }

    /**
     * Tests remove() method.
     */
    public function testRemove()
    {
        $token = $this->prophesize(AccessToken::class)->reveal();
        $em = $this->prophesize(EntityManagerInterface::class);

        $em->remove($token)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $repository = new AccessTokenRepository(
            $em->reveal(),
            new ClassMetadata(AccessToken::class)
        );

        $repository->remove($token);
    }
}
