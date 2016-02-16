<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\Services;

use Algatux\InfluxDbBundle\Model\PointsCollection;

/**
 * Class PointsCollectionStorage
 * @package Algatux\InfluxDbBundle\Services
 */
class PointsCollectionStorage implements CollectionStorageInterface
{

    /** @var array  */
    protected $storage;

    /**
     * PointsCollectionStorage constructor.
     */
    public function __construct()
    {
        $this->storage = [];
    }

    /**
     * @param PointsCollection $collection
     * @param string $writeMode
     */
    public function storeCollection(PointsCollection $collection, string $writeMode)
    {
        $this->checkStorageInitialization($writeMode, $collection->getPrecision());
        $this->mergeCollections($writeMode, $collection);
    }

    /**
     * @return array
     */
    public function getStoredCollections(): array
    {
        return $this->storage;
    }

    /**
     * @param string $getWriteMode
     * @param string $getPrecision
     */
    private function checkStorageInitialization(string $getWriteMode, string $getPrecision)
    {
        if (! isset($this->storage[$getWriteMode])) {
            $this->storage[$getWriteMode] = null;
        }

        if (!isset($this->storage[$getWriteMode][$getPrecision])) {
            $this->storage[$getWriteMode][$getPrecision] = new PointsCollection([], $getPrecision);
        }
    }

    /**
     * @param string $writemode
     * @param PointsCollection $points
     * @internal param InfluxDbEvent $event
     */
    private function mergeCollections(string $writemode, PointsCollection $points)
    {
        /** @var PointsCollection $actualCollection */
        $actualCollection = $this->storage[$writemode][$points->getPrecision()];

        $this->storage[$writemode][$points->getPrecision()] = new PointsCollection(array_merge(
            $actualCollection->toArray(),
            $points->toArray()
        ), $points->getPrecision());

    }
    
}
