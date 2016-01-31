<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\unit\Events;

use Algatux\InfluxDbBundle\Events\InfluxDbEvent;
use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface;
use InfluxDB\Database;

class InfluxDbEventTest extends \PHPUnit_Framework_TestCase
{

    public function test_event_interface()
    {
        $event = new InfluxDbEvent(new PointsCollection([1,2,3]), Database::PRECISION_SECONDS, ClientInterface::HTTP_CLIENT);

        $this->assertEquals(new PointsCollection([1,2,3]), $event->getPoints());
        $this->assertEquals(ClientInterface::HTTP_CLIENT, $event->getWriteClient());
        $this->assertEquals(Database::PRECISION_SECONDS, $event->getPayload());
    }
    
}
