<?php

namespace Algatux\InfluxDbBundle\unit\Services;

use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\CollectionStorageInterface;
use Algatux\InfluxDbBundle\Services\PointsCollectionStorage;
use InfluxDB\Database;

/**
 * Class PointCollectionStorageTest.
 */
class PointCollectionStorageTest extends \PHPUnit_Framework_TestCase
{
    public function test_instance_of_collections_storage_interface()
    {
        $this->assertInstanceOf(CollectionStorageInterface::class, new PointsCollectionStorage());
    }

    public function test_store_single_collection()
    {
        $points = new PointsCollection([1], Database::PRECISION_SECONDS);

        $store = new PointsCollectionStorage();
        $store->storeCollection($points, 'udp');

        $collections = $store->getStoredCollections();
        $this->assertCount(1, $collections['udp']['s']);
    }

    public function test_store_multiple_collection()
    {
        $points1 = new PointsCollection([1], Database::PRECISION_SECONDS);
        $points2 = new PointsCollection([2], Database::PRECISION_SECONDS);
        $points3 = new PointsCollection([3], Database::PRECISION_SECONDS);

        $store = new PointsCollectionStorage();
        $store->storeCollection($points1, 'udp');
        $store->storeCollection($points2, 'udp');
        $store->storeCollection($points3, 'udp');

        $collections = $store->getStoredCollections();

        $this->assertArrayHasKey('udp', $collections);
        $this->assertArrayHasKey('s', $collections['udp']);
        $this->assertInstanceOf(PointsCollection::class, $collections['udp']['s']);
        $this->assertCount(3, $collections['udp']['s']);
    }
}
