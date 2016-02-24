<?php

namespace Majora\Component\OAuth\Repository\ORM;

use Majora\Component\OAuth\Model\ApplicationInterface;
use Majora\Component\OAuth\Repository\ApplicationRepositoryInterface;
use Majora\Framework\Repository\Doctrine\BaseDoctrineRepository;

/**
 * Application repository implementation.
 */
class ApplicationRepository extends BaseDoctrineRepository implements ApplicationRepositoryInterface
{
    /**
     * @see TokenRepositoryInterface::persist()
     */
    public function persist(ApplicationInterface $application)
    {
        $em = $this->getEntityManager();

        $em->persist($application);
        $em->flush();
    }

    /**
     * @see TokenRepositoryInterface::remove()
     */
    public function remove(ApplicationInterface $application)
    {
        $em = $this->getEntityManager();

        $em->remove($application);
        $em->flush();
    }
}
