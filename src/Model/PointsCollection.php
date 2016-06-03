<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use InfluxDB\Database;
use InfluxDB\Point;

@trigger_error(
    'The '.__NAMESPACE__.'\PointsCollection class is deprecated since version 1.1 and will be removed in 2.0.',
    E_USER_DEPRECATED
);

/**
 * Class PointsCollection.
 *
 * @deprecated Since version 1.1, to be removed in 2.0.
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
