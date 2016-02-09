<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\unit\Events\Listeners;

use Algatux\InfluxDbBundle\Events\DeferredHttpEvent;
use Algatux\InfluxDbBundle\Events\DeferredUdpEvent;
use Algatux\InfluxDbBundle\Events\HttpEvent;
use Algatux\InfluxDbBundle\Events\Listeners\InfluxDbEventListener;
use Algatux\InfluxDbBundle\Events\UdpEvent;
use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\Clients\WriterClient;
use Algatux\InfluxDbBundle\Services\PointsCollectionStorage;
use InfluxDB\Database;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\Event;

class InfluxDbEventListenerTest extends \PHPUnit_Framework_TestCase
{

    public function test_listening_for_udp_infuxdb_event()
    {
        $event = new UdpEvent(new PointsCollection());

        $storage = $this->prophesize(PointsCollectionStorage::class);
        $storage->storeCollection(Argument::cetera())
            ->shouldNotBeCalled();

        $httpWriter = $this->prophesize(WriterClient::class);
        $httpWriter->write(Argument::cetera())
            ->shouldNotBeCalled();

        $udpWriter = $this->prophesize(WriterClient::class);
        $udpWriter->write(Argument::type(PointsCollection::class))
            ->shouldBeCalledTimes(1)
            ->willReturn(true);

        $listener = new InfluxDbEventListener($httpWriter->reveal(), $udpWriter->reveal(), $storage->reveal());
        $listener->onPointsCollected($event);
    }

    public function test_listening_for_http_infuxdb_event()
    {
        $event = new HttpEvent(new PointsCollection());

        $storage = $this->prophesize(PointsCollectionStorage::class);
        $storage->storeCollection(Argument::cetera())
            ->shouldNotBeCalled();

        $udpWriter = $this->prophesize(WriterClient::class);
        $udpWriter->write(Argument::cetera())
            ->shouldNotBeCalled();

        $httpWriter = $this->prophesize(WriterClient::class);
        $httpWriter->write(Argument::type(PointsCollection::class))
            ->shouldBeCalledTimes(1)
            ->willReturn(true);

        $listener = new InfluxDbEventListener($httpWriter->reveal(), $udpWriter->reveal(), $storage->reveal());
        $listener->onPointsCollected($event);
    }

    public function test_listening_for_deferred_udp_infuxdb_event()
    {
        $event1 = new DeferredUdpEvent(new PointsCollection([1]));
        $event2 = new DeferredUdpEvent(new PointsCollection([2]));
        $event3 = new DeferredUdpEvent(new PointsCollection([3]));

        $storage = $this->prophesize(PointsCollectionStorage::class);
        $storage->storeCollection(Argument::cetera())
            ->shouldBeCalledTimes(3);
        $storage->getStoredCollections()
            ->shouldBeCalledTimes(1)
            ->willReturn(['udp' => ['s' => new PointsCollection([1,2,3])]]);

        $httpWriter = $this->prophesize(WriterClient::class);
        $httpWriter->write(Argument::cetera())
            ->shouldNotBeCalled();

        $udpWriter = $this->prophesize(WriterClient::class);
        $udpWriter->write(new PointsCollection([1,2,3]))
            ->shouldBeCalledTimes(1)
            ->willReturn(true);

        $listener = new InfluxDbEventListener($httpWriter->reveal(), $udpWriter->reveal(), $storage->reveal());
        $listener->onPointsCollected($event1);
        $listener->onPointsCollected($event2);
        $listener->onPointsCollected($event3);

        $listener->onKernelTerminate(new Event());
    }

    public function test_listening_for_deferred_http_infuxdb_event()
    {
        $event1 = new DeferredHttpEvent(new PointsCollection([1]));
        $event2 = new DeferredHttpEvent(new PointsCollection([2]));
        $event3 = new DeferredHttpEvent(new PointsCollection([3]));

        $storage = $this->prophesize(PointsCollectionStorage::class);
        $storage->storeCollection(Argument::cetera())
            ->shouldBeCalledTimes(3);

        $storage->getStoredCollections()
            ->shouldBeCalledTimes(1)
            ->willReturn(['http' => ['s' => new PointsCollection([1,2,3])]]);

        $httpWriter = $this->prophesize(WriterClient::class);
        $httpWriter->write(new PointsCollection([1,2,3]))
            ->shouldBeCalledTimes(1)
            ->willReturn(true);

        $udpWriter = $this->prophesize(WriterClient::class);
        $udpWriter->write(Argument::cetera())
            ->shouldNotBeCalled();

        $listener = new InfluxDbEventListener($httpWriter->reveal(), $udpWriter->reveal(), $storage->reveal());
        $listener->onPointsCollected($event1);
        $listener->onPointsCollected($event2);
        $listener->onPointsCollected($event3);

        $listener->onKernelTerminate(new Event());
    }

}
