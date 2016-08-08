<?php

namespace Algatux\InfluxDbBundle\DependencyInjection;

use Algatux\InfluxDbBundle\Events\Listeners\InfluxDbEventListener;
use InfluxDB\Database;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
final class InfluxDbExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('factory.xml');

        // If the default connection if not defined, get the first one.
        $defaultConnection = isset($config['default_connection']) ? $config['default_connection'] : key($config['connections']);
        $this->buildConnections($container, $config, $defaultConnection);

        $this->setDefaultConnectionAlias($container, $defaultConnection);

        if (class_exists(WebProfilerBundle::class)) {
            $loader->load('data_collector.xml');
        }

        return $config;
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $connection The connection name
     * @param array            $config     The connection configuration
     * @param string           $protocol   The connection protocol ('http' or 'udp')
     */
    private function createConnection(ContainerBuilder $container, $connection, array $config, $protocol)
    {
        // Create the connection based from the abstract one.
        $connectionDefinition = new Definition(Database::class, [
            $config['database'],
            $config['host'],
            $config['http_port'],
            $config['udp_port'],
            $config['username'],
            $config['password'],
            'udp' === $protocol,
        ]);
        $connectionDefinition->setFactory([new Reference('algatux_influx_db.connection_factory'), 'createConnection']);

        // E.g.: algatux_influx_db.connection.default.http
        $container->setDefinition('algatux_influx_db.connection.'.$connection.'.'.$protocol, $connectionDefinition);
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $connection
     * @param string           $defaultConnection
     */
    private function createConnectionListener(ContainerBuilder $container, $connection, string $defaultConnection)
    {
        $listenerArguments = [
            $connection,
            $connection === $defaultConnection,
            new Reference('algatux_influx_db.connection.'.$connection.'.http'),
        ];
        if ($container->hasDefinition('algatux_influx_db.connection.'.$connection.'.udp')) {
            array_push($listenerArguments, new Reference('algatux_influx_db.connection.'.$connection.'.udp'));
        }

        $listenerDefinition = new Definition(InfluxDbEventListener::class, $listenerArguments);
        $listenerDefinition->addTag('kernel.event_listener', [
            'event' => 'influxdb.points_collected',
            'method' => 'onPointsCollected',
        ]);
        $listenerDefinition->addTag('kernel.event_listener', [
            'event' => 'kernel.terminate',
            'method' => 'onKernelTerminate',
        ]);

        $container->setDefinition('algatux_influx_db.event_listener.'.$connection, $listenerDefinition);
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     * @param string           $defaultConnection
     */
    private function buildConnections(ContainerBuilder $container, array $config, string $defaultConnection)
    {
        foreach ($config['connections'] as $connection => $connectionConfig) {
            $this->createConnection($container, $connection, $connectionConfig, 'http');
            if ($connectionConfig['udp']) {
                $this->createConnection($container, $connection, $connectionConfig, 'udp');
            }

            $this->createConnectionListener($container, $connection, $defaultConnection);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $defaultConnection
     */
    private function setDefaultConnectionAlias(ContainerBuilder $container, string $defaultConnection)
    {
        $container->setAlias(
            'algatux_influx_db.connection.http',
            'algatux_influx_db.connection.'.$defaultConnection.'.http'
        );

        if ($container->hasDefinition('algatux_influx_db.connection.'.$defaultConnection.'.udp')) {
            $container->setAlias(
                'algatux_influx_db.connection.udp',
                'algatux_influx_db.connection.'.$defaultConnection.'.udp'
            );
        }
    }
}
