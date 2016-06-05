<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\unit\Events\Listeners;

use Algatux\InfluxDbBundle\Events\DeferredHttpEvent;
use Algatux\InfluxDbBundle\Events\DeferredUdpEvent;
use Algatux\InfluxDbBundle\Events\HttpEvent;
use Algatux\InfluxDbBundle\Events\Listeners\InfluxDbEventListener;
use Algatux\InfluxDbBundle\Events\UdpEvent;
use InfluxDB\Database;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\Event;

class InfluxDbEventListenerTest extends \PHPUnit_Framework_TestCase
{
    public function test_listening_for_udp_infuxdb_event()
    {
        $event = new UdpEvent([], Database::PRECISION_SECONDS);

        $httpDatabase = $this->prophesize(Database::class);
        $httpDatabase->writePoints(Argument::cetera())
            ->shouldNotBeCalled();

        $udpDatabase = $this->prophesize(Database::class);
        $udpDatabase->writePoints(Argument::cetera())
            ->shouldBeCalledTimes(1)
            ->willReturn(true);

        $listener = new InfluxDbEventListener('default', true, $httpDatabase->reveal(), $udpDatabase->reveal());
        $listener->onPointsCollected($event);
    }

    public function test_listening_for_http_infuxdb_event()
    {
        $event = new HttpEvent([], Database::PRECISION_SECONDS);

        $udpDatabase = $this->prophesize(Database::class);
        $udpDatabase->writePoints(Argument::cetera())
            ->shouldNotBeCalled();

        $httpDatabase = $this->prophesize(Database::class);
        $httpDatabase->writePoints(Argument::cetera())
            ->shouldBeCalledTimes(1)
            ->willReturn(true);

        $listener = new InfluxDbEventListener('default', true, $httpDatabase->reveal(), $udpDatabase->reveal());
        $listener->onPointsCollected($event);
    }

    public function test_listening_for_deferred_udp_infuxdb_event()
    {
        $event1 = new DeferredUdpEvent([1], Database::PRECISION_SECONDS);
        $event2 = new DeferredUdpEvent([2], Database::PRECISION_SECONDS);
        $event3 = new DeferredUdpEvent([3], Database::PRECISION_SECONDS);

        $httpDatabase = $this->prophesize(Database::class);
        $httpDatabase->writePoints(Argument::cetera())
            ->shouldNotBeCalled();

        $udpDatabase = $this->prophesize(Database::class);
        $udpDatabase->writePoints(Argument::cetera())
            ->shouldBeCalledTimes(1)
            ->willReturn(true);

        $listener = new InfluxDbEventListener('default', true, $httpDatabase->reveal(), $udpDatabase->reveal());
        $listener->onPointsCollected($event1);
        $listener->onPointsCollected($event2);
        $listener->onPointsCollected($event3);

        $listener->onKernelTerminate(new Event());
    }

    public function test_listening_for_deferred_http_infuxdb_event()
    {
        $event1 = new DeferredHttpEvent([1], Database::PRECISION_SECONDS);
        $event2 = new DeferredHttpEvent([2], Database::PRECISION_SECONDS);
        $event3 = new DeferredHttpEvent([3], Database::PRECISION_SECONDS);

        $httpDatabase = $this->prophesize(Database::class);
        $httpDatabase->writePoints(Argument::cetera())
            ->shouldBeCalledTimes(1)
            ->willReturn(true);

        $udpDatabase = $this->prophesize(Database::class);
        $udpDatabase->writePoints(Argument::cetera())
            ->shouldNotBeCalled();

        $listener = new InfluxDbEventListener('default', true, $httpDatabase->reveal(), $udpDatabase->reveal());
        $listener->onPointsCollected($event1);
        $listener->onPointsCollected($event2);
        $listener->onPointsCollected($event3);

        $listener->onKernelTerminate(new Event());
    }

    public function test_it_should_do_nothing_if_not_same_connection()
    {
        $event = new UdpEvent([], Database::PRECISION_SECONDS, 'other_connection');

        $httpDatabase = $this->prophesize(Database::class);
        $httpDatabase->writePoints(Argument::cetera())
            ->shouldNotBeCalled();

        $udpDatabase = $this->prophesize(Database::class);
        $udpDatabase->writePoints(Argument::cetera())
            ->shouldNotBeCalled();

        $listener = new InfluxDbEventListener('default', true, $httpDatabase->reveal(), $udpDatabase->reveal());
        $listener->onPointsCollected($event);
    }
}
