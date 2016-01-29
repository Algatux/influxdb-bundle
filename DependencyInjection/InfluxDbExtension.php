<?php

namespace Algatux\InfluxDbBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

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

        $container->setParameter('influxdb.host', sprintf('%s',$config['host']));

        $container->setParameter('influxdb.writer_client.port', $config['writer_client']['port']);
        $container->setParameter('influxdb.reader_client.port', $config['reader_client']['port']);

        $container->setAlias('influxdb.writer_client', sprintf('algatux_influx_db.services.%s.writer_client', $config['writer_client']['type']));
        $container->setAlias('influxdb.reader_client', sprintf('algatux_influx_db.services.%s.reader_client', 'tcp'));

//        $ymlLoader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
//        $ymlLoader->load('services.yml');

        $xmlLoader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $xmlLoader->load('services.xml');
    }

}
