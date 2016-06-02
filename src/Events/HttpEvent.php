<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\Events;

use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface;

/**
 * Class HttpEvent
 * @package Algatux\InfluxDbBundle\Events
 */
class HttpEvent extends InfluxDbEvent
{

    /**
     * HttpEvent constructor.
     * @param PointsCollection $collection
     */
    public function __construct(PointsCollection $collection)
    {
        parent::__construct($collection, ClientInterface::HTTP_CLIENT);
    }

}
