<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Events;

use InfluxDB\Point;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class InfluxDbEvent.
 */
abstract class InfluxDbEvent extends Event
{
    const NAME = 'influxdb.points_collected';

    /**
     * @var Point[]
     */
    private $points;

    /**
     * @var string
     */
    private $precision;

    /**
     * @param Point[] $points
     * @param string  $precision
     */
    public function __construct(array $points, string $precision)
    {
        $this->points = $points;
        $this->precision = $precision;
    }

    /**
     * @return Point[]
     */
    final public function getPoints(): array
    {
        return $this->points;
    }

    /**
     * @return string
     */
    final public function getPrecision()
    {
        return $this->precision;
    }
}
