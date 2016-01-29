<?php

namespace Algatux\InfluxDbBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('influx_db');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
                ->scalarNode('host')->defaultValue('localhost')->end()
                ->arrayNode('writer_client')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('port')->defaultValue('4444')->end()
                        ->scalarNode('type')->defaultValue('udp')->end()
                    ->end()
                ->end()
                ->arrayNode('reader_client')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('port')->defaultValue('4444')->end()
//                        ->scalarNode('type')->defaultValue('tcp')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
