<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\unit\DependencyInjection;

use Algatux\InfluxDbBundle\DependencyInjection\InfluxDbExtension;
use Algatux\InfluxDbBundle\Events\Listeners\InfluxDbEventListener;
use Algatux\InfluxDbBundle\Exception\ConnectionNotFoundException;
use InfluxDB\Database;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class InfluxDbExtensionTest extends AbstractExtensionTestCase
{
    public function test_load()
    {
        $this->load([
            'host' => 'localhost',
            'database' => 'telegraf',
        ]);
        $this->compile();

        // Alias connections
        $this->assertContainerBuilderHasService('algatux_influx_db.connection.http', Database::class);
        $httpDefaultConnection = $this->container->get('algatux_influx_db.connection.http');
        $this->assertInstanceOf(Database::class, $httpDefaultConnection);
        $this->assertSame('telegraf', $httpDefaultConnection->getName());

        $this->assertContainerBuilderNotHasService('algatux_influx_db.connection.udp');

        // 'default' connections
        $this->assertContainerBuilderHasService('algatux_influx_db.connection.default.http', Database::class);
        $httpDefaultConnection = $this->container->get('algatux_influx_db.connection.default.http');
        $this->assertInstanceOf(Database::class, $httpDefaultConnection);
        $this->assertSame('telegraf', $httpDefaultConnection->getName());

        $this->assertContainerBuilderNotHasService('algatux_influx_db.connection.default.udp');

        // Listener
        $this->assertContainerBuilderHasService('algatux_influx_db.event_listener.default', InfluxDbEventListener::class);
    }

    public function test_load_with_ssl()
    {
        $this->load([
            'host' => 'localhost',
            'database' => 'telegraf',
            'ssl' => true,
        ]);
        $this->compile();

        // Alias connections
        $this->assertContainerBuilderHasService('algatux_influx_db.connection.http', Database::class);
        $httpDefaultConnection = $this->container->get('algatux_influx_db.connection.http');
        $this->assertInstanceOf(Database::class, $httpDefaultConnection);
        $this->assertSame('telegraf', $httpDefaultConnection->getName());

        $this->assertContainerBuilderNotHasService('algatux_influx_db.connection.udp');

        // 'default' connections
        $this->assertContainerBuilderHasService('algatux_influx_db.connection.default.http', Database::class);
        $httpDefaultConnection = $this->container->get('algatux_influx_db.connection.default.http');
        $this->assertInstanceOf(Database::class, $httpDefaultConnection);
        $this->assertSame('telegraf', $httpDefaultConnection->getName());

        $this->assertContainerBuilderNotHasService('algatux_influx_db.connection.default.udp');

        // Listener
        $this->assertContainerBuilderHasService('algatux_influx_db.event_listener.default', InfluxDbEventListener::class);
    }

    public function test_load_with_udp()
    {
        $this->load([
            'host' => 'localhost',
            'database' => 'telegraf',
            'udp' => true,
        ]);
        $this->compile();

        // Alias connections
        $this->assertContainerBuilderHasService('algatux_influx_db.connection.http', Database::class);
        $httpDefaultConnection = $this->container->get('algatux_influx_db.connection.http');
        $this->assertInstanceOf(Database::class, $httpDefaultConnection);
        $this->assertSame('telegraf', $httpDefaultConnection->getName());
        $this->assertSame(
            $httpDefaultConnection,
            $this->container->get('algatux_influx_db.connection_registry')->getDefaultHttpConnection()
        );

        $this->assertContainerBuilderHasService('algatux_influx_db.connection.udp', Database::class);
        $udpDefaultConnection = $this->container->get('algatux_influx_db.connection.udp');
        $this->assertInstanceOf(Database::class, $udpDefaultConnection);
        $this->assertSame('telegraf', $udpDefaultConnection->getName());
        $this->assertSame(
            $udpDefaultConnection,
            $this->container->get('algatux_influx_db.connection_registry')->getDefaultUdpConnection()
        );

        // 'default' connections
        $this->assertContainerBuilderHasService('algatux_influx_db.connection.default.http', Database::class);
        $httpDefaultConnection = $this->container->get('algatux_influx_db.connection.default.http');
        $this->assertInstanceOf(Database::class, $httpDefaultConnection);
        $this->assertSame('telegraf', $httpDefaultConnection->getName());
        $this->assertSame(
            $httpDefaultConnection,
            $this->container->get('algatux_influx_db.connection_registry')->getHttpConnection('default')
        );

        $this->assertContainerBuilderHasService('algatux_influx_db.connection.default.udp', Database::class);
        $udpDefaultConnection = $this->container->get('algatux_influx_db.connection.default.udp');
        $this->assertInstanceOf(Database::class, $udpDefaultConnection);
        $this->assertSame('telegraf', $udpDefaultConnection->getName());
        $this->assertSame(
            $udpDefaultConnection,
            $this->container->get('algatux_influx_db.connection_registry')->getUdpConnection('default')
        );

        // Listener
        $this->assertContainerBuilderHasService('algatux_influx_db.event_listener.default', InfluxDbEventListener::class);
    }

    public function test_load_multiple_connections()
    {
        $config = [
            'default_connection' => 'other_host',
            'connections' => [
                'default' => [
                    'host' => 'localhost',
                    'database' => 'telegraf',
                ],
                'same_client' => [
                    'host' => 'localhost',
                    'database' => 'telegraf_2',
                ],
                'other_http_port' => [
                    'host' => 'localhost',
                    'http_port' => 8080,
                    'database' => 'telegraf',
                ],
                'other_auth' => [
                    'host' => 'localhost',
                    'username' => 'john',
                    'password' => 'passwd',
                    'database' => 'telegraf',
                ],
                'other_host' => [
                    'host' => 'remote',
                    'database' => 'other_database',
                    'udp' => true,
                ],
            ],
        ];
        $this->load($config);

        // Alias connections
        $this->assertContainerBuilderHasService('algatux_influx_db.connection.http', Database::class);
        $httpDefaultConnection = $this->container->get('algatux_influx_db.connection.http');
        $this->assertInstanceOf(Database::class, $httpDefaultConnection);
        $this->assertSame('other_database', $httpDefaultConnection->getName());

        $this->assertContainerBuilderHasService('algatux_influx_db.connection.udp', Database::class);
        $udpDefaultConnection = $this->container->get('algatux_influx_db.connection.udp');
        $this->assertInstanceOf(Database::class, $udpDefaultConnection);
        $this->assertSame('other_database', $udpDefaultConnection->getName());

        foreach ($config['connections'] as $connection => $connectionConfig) {
            $this->assertContainerBuilderHasService('algatux_influx_db.connection.'.$connection.'.http', Database::class);
            $httpDefaultConnection = $this->container->get('algatux_influx_db.connection.'.$connection.'.http');
            $this->assertInstanceOf(Database::class, $httpDefaultConnection);
            $this->assertSame($connectionConfig['database'], $httpDefaultConnection->getName());

            if (array_key_exists('udp', $connectionConfig) && $connectionConfig['udp']) {
                $this->assertContainerBuilderHasService('algatux_influx_db.connection.'.$connection.'.udp', Database::class);
                $udpDefaultConnection = $this->container->get('algatux_influx_db.connection.'.$connection.'.udp');
                $this->assertInstanceOf(Database::class, $udpDefaultConnection);
                $this->assertSame($connectionConfig['database'], $udpDefaultConnection->getName());
            } else {
                $this->assertContainerBuilderNotHasService('algatux_influx_db.connection.'.$connection.'.udp');
            }

            $this->assertContainerBuilderHasService('algatux_influx_db.event_listener.'.$connection, InfluxDbEventListener::class);
        }

        $this->assertAttributeCount(5, 'clients', $this->container->get('algatux_influx_db.connection_factory'));
    }

    public function test_registry_with_undefined_connection()
    {
        $this->load([
            'host' => 'localhost',
            'database' => 'telegraf',
        ]);
        $this->compile();

        $this->expectException(ConnectionNotFoundException::class);
        $this->container->get('algatux_influx_db.connection_registry')->getHttpConnection('undefined');
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
