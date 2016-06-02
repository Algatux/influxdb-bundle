<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Events;

use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface;

/**
 * Class DeferredHttpEvent.
 */
class DeferredHttpEvent extends DeferredInfluxDbEvent
{
    public function __construct(PointsCollection $collection)
    {
        parent::__construct($collection, ClientInterface::HTTP_CLIENT);
    }
}
