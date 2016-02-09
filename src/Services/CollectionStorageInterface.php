<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\Services;

use Algatux\InfluxDbBundle\Model\PointsCollection;

/**
 * Interface CollectionStorageInterface
 * @package Algatux\InfluxDbBundle\Services
 */
interface CollectionStorageInterface
{

    public function storeCollection(PointsCollection $collection, $writeMode);

    public function getStoredCollections(): array;

}
