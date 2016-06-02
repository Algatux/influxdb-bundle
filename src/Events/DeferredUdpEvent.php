<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\Events;

use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface;

/**
 * Class DeferredUdpEvent
 * @package Algatux\InfluxDbBundle\Events
 */
class DeferredUdpEvent extends DeferredInfluxDbEvent
{

    /**
     * DeferredUdpEvent constructor.
     * @param PointsCollection $collection
     */
    public function __construct(PointsCollection $collection)
    {
        parent::__construct($collection, ClientInterface::UDP_CLIENT);
    }

}
