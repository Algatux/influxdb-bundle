<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\Services\Clients\Contracts;
use Algatux\InfluxDbBundle\Model\PointsCollection;

/**
 * Interface WriterInterface
 * @package Algatux\InfluxDbBundle\Services\Clients\Contracts
 */
interface WriterInterface
{

    /**
     * @param PointsCollection $points
     * @return bool
     */
    public function write(PointsCollection $points): bool;

}