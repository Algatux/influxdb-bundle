<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\unit\DependencyInjection;

use Algatux\InfluxDbBundle\DependencyInjection\InfluxDbExtension;
use Algatux\InfluxDbBundle\Events\Listeners\InfluxDbEventListener;
use Algatux\InfluxDbBundle\Services\Clients\InfluxDbClientFactory;
use Algatux\InfluxDbBundle\Services\Clients\WriterClient;
use InfluxDB\Database;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class InfluxDbExtensionTest extends AbstractExtensionTestCase
{
    public function test_load()
    {
        $this->load();

        $this->assertContainerBuilderHasService('algatux_influx_db.database.http', Database::class);
        $httpDatabase = $this->container->get('algatux_influx_db.database.http');
        $this->assertInstanceOf(Database::class, $httpDatabase);
        $this->assertSame('telegraf', $httpDatabase->getName());

        $this->assertContainerBuilderHasService('algatux_influx_db.database.udp', Database::class);
        $udpDatabase = $this->container->get('algatux_influx_db.database.udp');
        $this->assertInstanceOf(Database::class, $udpDatabase);
        $this->assertSame('telegraf', $udpDatabase->getName());
    }

    public function test_load_use_events()
    {
        $this->load([
            'use_events' => true,
        ]);

        $this->assertContainerBuilderHasService('algatux_influx_db.events_listeners.influx_db_event_listener', InfluxDbEventListener::class);
    }

    protected function getMinimalConfiguration()
    {
        return [
            'host' => 'localhost',
            'database' => 'telegraf',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return [
            new InfluxDbExtension(),
        ];
    }
}
