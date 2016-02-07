<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\unit\Events;

use Algatux\InfluxDbBundle\Events\HttpEvent;
use Algatux\InfluxDbBundle\Events\InfluxDbEvent;
use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface;
use InfluxDB\Database;

class InfluxDbEventTest extends \PHPUnit_Framework_TestCase
{

    public function test_event_interface()
    {
        $event = new HttpEvent(new PointsCollection([1,2,3]), ClientInterface::HTTP_CLIENT);

        $this->assertInstanceOf(InfluxDbEvent::class, $event);

        $this->assertEquals(new PointsCollection([1,2,3]), $event->getPoints());
        $this->assertEquals(ClientInterface::HTTP_CLIENT, $event->getWriteMode());
    }
    
}
