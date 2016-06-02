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

        $rootNode
            ->children()
                ->scalarNode('host')->end()
                ->scalarNode('database')->end()
                ->scalarNode('udp_port')->defaultValue('4444')->end()
                ->scalarNode('http_port')->defaultValue('8086')->end()
                ->scalarNode('username')->defaultValue('')->end()
                ->scalarNode('password')->defaultValue('')->end()
                ->booleanNode('use_events')->defaultFalse()->end()
            ->end();

        return $treeBuilder;
    }

}

