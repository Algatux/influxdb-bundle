<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\unit\Events\Listeners;

use Algatux\InfluxDbBundle\Events\InfluxDbEvent;
use Algatux\InfluxDbBundle\Events\Listeners\InfluxDbEventListener;
use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface;
use Algatux\InfluxDbBundle\Services\Clients\WriterClient;
use InfluxDB\Database;
use Prophecy\Argument;

class InfluxDbEventListenerTest extends \PHPUnit_Framework_TestCase
{

    public function test_listening_for_udp_infuxdb_event()
    {
        $event = new InfluxDbEvent(new PointsCollection(), Database::PRECISION_SECONDS, ClientInterface::UDP_CLIENT);

        $httpWriter = $this->prophesize(WriterClient::class);
        $httpWriter->write(Argument::cetera())
            ->shouldNotBeCalled();

        $udpWriter = $this->prophesize(WriterClient::class);
        $udpWriter->write(Argument::type(PointsCollection::class), Database::PRECISION_SECONDS)
            ->shouldBeCalledTimes(1)
            ->willReturn(true);

        $listener = new InfluxDbEventListener($httpWriter->reveal(), $udpWriter->reveal());
        $listener->onInfluxDbEventDispatched($event);
    }

    public function test_listening_for_http_infuxdb_event()
    {
        $event = new InfluxDbEvent(new PointsCollection(), Database::PRECISION_SECONDS, ClientInterface::HTTP_CLIENT);

        $udpWriter = $this->prophesize(WriterClient::class);
        $udpWriter->write(Argument::cetera())
            ->shouldNotBeCalled();

        $httpWriter = $this->prophesize(WriterClient::class);
        $httpWriter->write(Argument::type(PointsCollection::class), Database::PRECISION_SECONDS)
            ->shouldBeCalledTimes(1)
            ->willReturn(true);

        $listener = new InfluxDbEventListener($httpWriter->reveal(), $udpWriter->reveal());
        $listener->onInfluxDbEventDispatched($event);
    }

}
