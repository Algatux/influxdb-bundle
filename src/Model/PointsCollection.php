<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use InfluxDB\Database;
use InfluxDB\Point;

/**
 * Class PointsCollection.
 */
class PointsCollection extends ArrayCollection
{
    /**
     * @var string
     */
    public $precision;

    /**
     * Initializes a new ArrayCollection.
     *
     * @param Point[] $elements
     * @param string  $precision
     */
    public function __construct(array $elements = [], string $precision = Database::PRECISION_SECONDS)
    {
        parent::__construct($elements);
        $this->precision = $precision;
    }

    /**
     * @return string
     */
    public function getPrecision()
    {
        return $this->precision;
    }
}
