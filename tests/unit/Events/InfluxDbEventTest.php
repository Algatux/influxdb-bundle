<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\unit\Events;

use Algatux\InfluxDbBundle\Events\AbstractInfluxDbEvent;
use Algatux\InfluxDbBundle\Events\HttpEvent;
use InfluxDB\Database;
use PHPUnit\Framework\TestCase;

class InfluxDbEventTest extends TestCase
{
    /**
     * @group legacy
     */
    public function test_event_interface()
    {
        $event = new HttpEvent([1, 2, 3], Database::PRECISION_SECONDS);

        $this->assertInstanceOf(AbstractInfluxDbEvent::class, $event);

        $this->assertEquals([1, 2, 3], $event->getPoints());
        $this->assertEquals(Database::PRECISION_SECONDS, $event->getPrecision());
        $this->assertNull($event->getConnection());
    }

    /**
     * @group legacy
     */
    public function test_event_interface_with_connection()
    {
        $event = new HttpEvent([1, 2, 3], Database::PRECISION_SECONDS, 'telegraf');

        $this->assertInstanceOf(AbstractInfluxDbEvent::class, $event);

        $this->assertEquals([1, 2, 3], $event->getPoints());
        $this->assertEquals(Database::PRECISION_SECONDS, $event->getPrecision());
        $this->assertEquals('telegraf', $event->getConnection());
    }
}
