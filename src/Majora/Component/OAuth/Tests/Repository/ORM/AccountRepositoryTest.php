<?php

namespace Majora\Component\OAuth\Repository\Loader\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Majora\Component\OAuth\Entity\Account;
use Majora\Component\OAuth\Repository\ORM\AccountRepository;

/**
 * Unit tests class for Majora\Component\OAuth\Repository\ORM\AccountRepository.
 *
 * @see Majora\Component\OAuth\Repository\ORM\AccountRepository
 */
class AccountRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests persist() method.
     */
    public function testPersist()
    {
        $account = new Account();
        $em = $this->prophesize(EntityManagerInterface::class);

        $em->persist($account)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $repository = new AccountRepository(
            $em->reveal(),
            new ClassMetadata(Account::class)
        );

        $repository->persist($account);
    }

    /**
     * Tests remove() method.
     */
    public function testRemove()
    {
        $account = new Account();
        $em = $this->prophesize(EntityManagerInterface::class);

        $em->remove($account)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $repository = new AccountRepository(
            $em->reveal(),
            new ClassMetadata(Account::class)
        );

        $repository->remove($account);
    }
}
