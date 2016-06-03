<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\unit\DependencyInjection;

use Algatux\InfluxDbBundle\DependencyInjection\InfluxDbExtension;
use Algatux\InfluxDbBundle\Events\Listeners\InfluxDbEventListener;
use Algatux\InfluxDbBundle\Services\Clients\InfluxDbClientFactory;
use Algatux\InfluxDbBundle\Services\Clients\WriterClient;
use Algatux\InfluxDbBundle\Services\PointsCollectionStorage;
use InfluxDB\Database;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class InfluxDbExtensionTest extends AbstractExtensionTestCase
{
    public function test_load()
    {
        $this->load();

        $this->assertContainerBuilderHasService('algatux_influx_db.services_clients.influx_db_client_factory', InfluxDbClientFactory::class);
        $this->assertContainerBuilderHasService('algatux_influx_db.client.udp.writer_client', WriterClient::class);
        $this->assertContainerBuilderHasService('algatux_influx_db.client.http.writer_client', WriterClient::class);
        $this->assertContainerBuilderNotHasService('algatux_influx_db.events_listeners.influx_db_event_listener');
        $this->assertContainerBuilderNotHasService('algatux_influx_db.services.points_collection_storage');

        $this->assertAttributeInstanceOf(Database::class, 'database', $this->container->get('algatux_influx_db.client.http.writer_client'));
        $this->assertAttributeInstanceOf(Database::class, 'database', $this->container->get('algatux_influx_db.client.udp.writer_client'));
    }

    public function test_load_use_events()
    {
        $this->load([
            'use_events' => true,
        ]);

        $this->assertContainerBuilderHasService('algatux_influx_db.events_listeners.influx_db_event_listener', InfluxDbEventListener::class);
        $this->assertContainerBuilderHasService('algatux_influx_db.services.points_collection_storage', PointsCollectionStorage::class);
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
