<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Events;

use InfluxDB\Point;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class AbstractInfluxDbEvent.
 */
abstract class AbstractInfluxDbEvent extends Event
{
    const NAME = 'influxdb.points_collected';

    /** @var Point[] */
    protected $points;

    /** @var string */
    private $collectionPrecision;
    /**
     * @var bool
     */
    private $deferred;

    /**
     * AbstractInfluxDbEvent constructor.
     *
     * @param Point[]   $collection
     * @param string    $collectionPrecision
     * @param bool      $deferred
     */
    public function __construct(array $collection, string $collectionPrecision, bool $deferred = false)
    {
        $this->points = $collection;
        $this->collectionPrecision = $collectionPrecision;
        $this->deferred = $deferred;
    }

    /**
     * @return string
     */
    public function getCollectionPrecision(): string
    {
        return $this->collectionPrecision;
    }

    /**
     * @return Point[]
     */
    public function getPoints(): array
    {
        return $this->points;
    }

    /**
     * @return bool
     */
    public function isWriteDeferred(): bool
    {
        return $this->deferred;
    }
}
