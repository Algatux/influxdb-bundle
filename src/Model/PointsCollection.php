<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use InfluxDB\Database;

/**
 * Class PointsCollection
 * @package Algatux\InfluxDbBundle\Model
 */
class PointsCollection extends ArrayCollection
{

    public $precision;

    /**
     * Initializes a new ArrayCollection.
     *
     * @param array $elements
     * @param string $precision
     */
    public function __construct(array $elements = array(), string $precision = Database::PRECISION_SECONDS)
    {
        parent::__construct($elements);
        $this->precision = $precision;
    }

    /**
     * @return mixed
     */
    public function getPrecision()
    {
        return $this->precision;
    }

}
