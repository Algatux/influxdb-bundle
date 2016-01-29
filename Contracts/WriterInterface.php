<?php
declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Contracts;

use Algatux\InfluxDbBundle\Model\PointsCollection;

/**
 * Interface WriterInterface
 * @package Algatux\InfluxDbBundle\Contracts
 */
interface WriterInterface
{

    /**
     * @param PointsCollection $collection
     * @param string $payload
     * @return bool
     */
    public function write(PointsCollection $collection, string $payload): bool;

}
