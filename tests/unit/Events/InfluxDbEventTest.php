<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\unit\Events;

use Algatux\InfluxDbBundle\Events\HttpEventAbstract;
use Algatux\InfluxDbBundle\Events\AbstractInfluxDbEvent;
use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface;

class InfluxDbEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group legacy
     */
    public function test_event_interface()
    {
        $event = new HttpEventAbstract(new PointsCollection([1, 2, 3]), ClientInterface::HTTP_CLIENT);

        $this->assertInstanceOf(AbstractInfluxDbEvent::class, $event);

        $this->assertEquals(new PointsCollection([1, 2, 3]), $event->getPoints());
        $this->assertEquals(ClientInterface::HTTP_CLIENT, $event->getWriteMode());
    }
}
