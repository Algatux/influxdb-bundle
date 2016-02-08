<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\Events;

use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class InfluxDbEvent
 * @package Algatux\InfluxDbBundle\Events
 */
abstract class InfluxDbEvent extends Event
{

    const NAME = 'influxdb.points_collected';

    /** @var string  */
    protected $writeMode;

    /** @var PointsCollection  */
    protected $points;

    /**
     * InfluxDbEvent constructor.
     * @param PointsCollection $collection
     * @param string $writeMode
     */
    public function __construct(PointsCollection $collection, string $writeMode = ClientInterface::UDP_CLIENT)
    {
        $this->points = $collection;
        $this->writeMode = $writeMode;
    }

    /**
     * @return string
     */
    public function getWriteMode(): string
    {
        return $this->writeMode;
    }

    /**
     * @return PointsCollection
     */
    public function getPoints(): PointsCollection
    {
        return $this->points;
    }

}
