<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\unit\Events;

use Algatux\InfluxDbBundle\Events\HttpEvent;
use Algatux\InfluxDbBundle\Events\InfluxDbEvent;
use InfluxDB\Database;

class InfluxDbEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group legacy
     */
    public function test_event_interface()
    {
        $event = new HttpEvent([1, 2, 3], Database::PRECISION_SECONDS);

        $this->assertInstanceOf(InfluxDbEvent::class, $event);

        $this->assertEquals([1, 2, 3], $event->getPoints());
        $this->assertEquals(Database::PRECISION_SECONDS, $event->getPrecision());
    }
}
