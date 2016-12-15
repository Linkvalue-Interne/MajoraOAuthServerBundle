<?php

namespace Majora\Component\OAuth\Repository\Loader\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Majora\Component\OAuth\Entity\Application;
use Majora\Component\OAuth\Repository\ORM\ApplicationRepository;

/**
 * Unit tests class for Majora\Component\OAuth\Repository\ORM\ApplicationRepository.
 *
 * @see Majora\Component\OAuth\Repository\ORM\ApplicationRepository
 */
class ApplicationRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests persist() method.
     */
    public function testPersist()
    {
        $application = new Application();
        $em = $this->prophesize(EntityManagerInterface::class);

        $em->persist($application)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $repository = new ApplicationRepository(
            $em->reveal(),
            new ClassMetadata(Application::class)
        );

        $repository->persist($application);
    }

    /**
     * Tests remove() method.
     */
    public function testRemove()
    {
        $application = new Application();
        $em = $this->prophesize(EntityManagerInterface::class);

        $em->remove($application)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $repository = new ApplicationRepository(
            $em->reveal(),
            new ClassMetadata(Application::class)
        );

        $repository->remove($application);
    }
}
