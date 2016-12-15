<?php

namespace Majora\Component\OAuth\Loader\InMemory;

use Majora\Component\OAuth\Loader\TokenLoaderInterface;
use Majora\Framework\Loader\Bridge\InMemory\InMemoryLoaderTrait;
use Majora\Framework\Date\Clock;
use Majora\Framework\Model\EntityCollection;
use Majora\Framework\Normalizer\MajoraNormalizer;

/**
 * ORM token loading implementation.
 */
class TokenLoader implements TokenLoaderInterface
{
    use InMemoryLoaderTrait;

    /**
     * @var Clock
     */
    protected $clock;

    /**
     * Construct.
     *
     * @param string           $collectionClass
     * @param MajoraNormalizer $normalizer
     * @param Clock           $clock
     */
    public function __construct($collectionClass, MajoraNormalizer $normalizer, Clock $clock)
    {
        if (empty($collectionClass) || !class_exists($collectionClass)) {
            throw new \InvalidArgumentException(sprintf(
                'You must provide a valid EntityCollection class name, "%s" given.',
                $collectionClass
            ));
        }
        $this->entityCollection = new $collectionClass();
        if (!$this->entityCollection instanceof EntityCollection) {
            throw new \InvalidArgumentException(sprintf(
                'Provided class name is not an Majora\Framework\Model\EntityCollection, "%s" given.',
                $collectionClass
            ));
        }

        $this->normalizer = $normalizer;
        $this->clock = $clock;
    }

    /**
     * @see TokenLoaderInterface::retrieveByHash()
     */
    public function retrieveByHash($hash)
    {

    }

    /**
     * @see TokenLoaderInterface::retrieveExpired()
     */
    public function retrieveExpired(\DateTime $datetime = null)
    {

    }
}
