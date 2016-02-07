<?php

namespace Algatux\InfluxDbBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class InfluxDbExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('influx_db.udp.port', $config['udp_port']);
        $container->setParameter('influx_db.http.port', $config['http_port']);
        $container->setParameter('influx_db.host', $config['host']);
        $container->setParameter('influx_db.database', $config['database']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        if ($config['use_events'] === true) {
            $loader->load('listeners.xml');
        }

        return $config;
    }

}
