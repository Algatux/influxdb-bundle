<?php

namespace Algatux\InfluxDbBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('influx_db');

        $rootNode
            ->beforeNormalization()
                ->ifTrue(function ($v) {
                    return is_array($v) && !array_key_exists('connections', $v);
                })
                ->then(function ($v) {
                    $excludedKeys = ['default_connection'];
                    $connection = [];
                    foreach ($v as $key => $value) {
                        if (in_array($key, $excludedKeys, true)) {
                            continue;
                        }
                        $connection[$key] = $v[$key];
                        unset($v[$key]);
                    }
                    $v['connections'] = ['default' => $connection];

                    return $v;
                })
            ->end()
            ->children()
                ->scalarNode('default_connection')->info('If not defined, the first connection will be taken.')->end()
                ->arrayNode('connections')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('host')
                                ->isRequired()
                                ->info('Your InfluxDB host address')
                            ->end()
                            ->scalarNode('database')
                                ->isRequired()
                                ->info('Your InfluxDB database name')
                            ->end()
                            ->booleanNode('udp')
                                ->defaultFalse()
                                ->info('Set it to true to activate the UDP connection')
                            ->end()
                            ->booleanNode('ssl')
                                ->defaultFalse()
                                ->info('Set it to true to enable SSL on HTTP connections (required for Influx Cloud)')
                            ->end()
                            ->integerNode('udp_port')->defaultValue(4444)->end()
                            ->integerNode('http_port')->defaultValue(8086)->end()
                            ->scalarNode('username')->defaultValue('')->end()
                            ->scalarNode('password')->defaultValue('')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
