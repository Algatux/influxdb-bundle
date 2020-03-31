<?php

declare(strict_types=1);

namespace Yproximite\InfluxDbBundle\Tests\unit;

use Yproximite\InfluxDbBundle\DependencyInjection\Configuration;
use Yproximite\InfluxDbBundle\DependencyInjection\InfluxDbExtension;
use Yproximite\InfluxDbBundle\Events\Listeners\InfluxDbEventListener;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class ConfigurationTest extends AbstractExtensionConfigurationTestCase
{
    public function test_empty_configuration_process()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('The child node "host" at path "influx_db.connections.default" must be configured.');

        $this->assertProcessedConfigurationEquals([], [
            __DIR__.'/../../fixtures/config/config_empty.yml',
        ]);
    }

    public function test_minimal_configuration_process()
    {
        $expectedConfiguration = [
            'connections' => [
                'default' => [
                    'host' => 'localhost',
                    'database' => 'telegraf',
                    'udp' => false,
                    'ssl' => false,
                    'ssl_verification' => false,
                    'udp_port' => '4444',
                    'http_port' => '8086',
                    'username' => '',
                    'password' => '',
                    'timeout' => 0.0,
                    'connect_timeout' => 0.0,
                    'listener_enabled' => true,
                    'listener_class' => InfluxDbEventListener::class,
                ],
            ],
        ];

        $this->assertProcessedConfigurationEquals($expectedConfiguration, [
            __DIR__.'/../../fixtures/config/config_minimal.yml',
        ]);
    }

    public function test_minimal_configuration_with_ssl_process()
    {
        $expectedConfiguration = [
            'connections' => [
                'default' => [
                    'host' => 'localhost',
                    'database' => 'telegraf',
                    'udp' => false,
                    'ssl' => true,
                    'ssl_verification' => true,
                    'udp_port' => '4444',
                    'http_port' => '8086',
                    'username' => '',
                    'password' => '',
                    'timeout' => 0.0,
                    'connect_timeout' => 0.0,
                    'listener_enabled' => true,
                    'listener_class' => InfluxDbEventListener::class,
                ],
            ],
        ];

        $this->assertProcessedConfigurationEquals($expectedConfiguration, [
            __DIR__.'/../../fixtures/config/config_minimal_ssl.yml',
        ]);
    }

    public function test_full_udp_configuration_process()
    {
        $expectedConfiguration = [
            'default_connection' => 'default',
            'connections' => [
                'default' => [
                    'host' => 'localhost',
                    'database' => 'telegraf',
                    'udp' => true,
                    'ssl' => false,
                    'ssl_verification' => false,
                    'udp_port' => '1337',
                    'http_port' => '42',
                    'username' => 'foo',
                    'password' => 'bar',
                    'timeout' => 1.5,
                    'connect_timeout' => 1,
                    'listener_enabled' => true,
                    'listener_class' => InfluxDbEventListener::class,
                ],
            ],
        ];

        $this->assertProcessedConfigurationEquals($expectedConfiguration, [
            __DIR__.'/../../fixtures/config/config_full_udp.yml',
        ]);
    }

    public function test_full_ssl_configuration_process()
    {
        $expectedConfiguration = [
            'default_connection' => 'default',
            'connections' => [
                'default' => [
                    'host' => 'localhost',
                    'database' => 'telegraf',
                    'udp' => false,
                    'ssl' => true,
                    'ssl_verification' => true,
                    'udp_port' => '1337',
                    'http_port' => '42',
                    'username' => 'foo',
                    'password' => 'bar',
                    'timeout' => 0.0,
                    'connect_timeout' => 0.0,
                    'listener_enabled' => true,
                    'listener_class' => InfluxDbEventListener::class,
                ],
            ],
        ];

        $this->assertProcessedConfigurationEquals($expectedConfiguration, [
            __DIR__.'/../../fixtures/config/config_full_ssl.yml',
        ]);
    }

    public function test_multiple_connections_configuration_process()
    {
        $expectedConfiguration = [
            'default_connection' => 'test',
            'connections' => [
                'default' => [
                    'database' => 'telegraf',
                    'host' => 'localhost',
                    'udp' => false,
                    'ssl' => false,
                    'ssl_verification' => false,
                    'udp_port' => 4444,
                    'http_port' => 8086,
                    'username' => 'foo',
                    'password' => 'bar',
                    'timeout' => 1,
                    'connect_timeout' => 0.0,
                    'listener_enabled' => true,
                    'listener_class' => InfluxDbEventListener::class,
                ],
                'listener_disabled' => [
                    'database' => 'telegraf',
                    'host' => 'localhost',
                    'udp' => false,
                    'ssl' => false,
                    'ssl_verification' => false,
                    'udp_port' => 4444,
                    'http_port' => 8086,
                    'username' => 'foo',
                    'password' => 'bar',
                    'timeout' => 1,
                    'connect_timeout' => 0.0,
                    'listener_enabled' => false,
                    'listener_class' => InfluxDbEventListener::class,
                ],
                'listener_class_override' => [
                    'database' => 'telegraf',
                    'host' => 'localhost',
                    'udp' => false,
                    'ssl' => false,
                    'ssl_verification' => false,
                    'udp_port' => 4444,
                    'http_port' => 8086,
                    'username' => 'foo',
                    'password' => 'bar',
                    'timeout' => 1,
                    'connect_timeout' => 0.0,
                    'listener_enabled' => true,
                    'listener_class' => 'Acme\CustomInfluxDbEventListener',
                ],
                'udp' => [
                    'database' => 'test',
                    'host' => 'localhost',
                    'udp' => true,
                    'ssl' => false,
                    'ssl_verification' => false,
                    'udp_port' => 4444,
                    'http_port' => 8086,
                    'username' => 'foo',
                    'password' => 'bar',
                    'timeout' => 0.0,
                    'connect_timeout' => 1,
                    'listener_enabled' => true,
                    'listener_class' => InfluxDbEventListener::class,
                ],
                'ssl' => [
                    'database' => 'test',
                    'host' => 'localhost',
                    'udp' => false,
                    'ssl' => true,
                    'ssl_verification' => true,
                    'udp_port' => 4444,
                    'http_port' => 8086,
                    'username' => 'foo',
                    'password' => 'bar',
                    'timeout' => 0.0,
                    'connect_timeout' => 0.0,
                    'listener_enabled' => true,
                    'listener_class' => InfluxDbEventListener::class,
                ],
                'ssl_no_check' => [
                    'database' => 'test',
                    'host' => 'localhost',
                    'udp' => false,
                    'ssl' => true,
                    'ssl_verification' => false,
                    'udp_port' => 4444,
                    'http_port' => 8086,
                    'username' => 'foo',
                    'password' => 'bar',
                    'timeout' => 0.0,
                    'connect_timeout' => 1,
                    'listener_enabled' => true,
                    'listener_class' => InfluxDbEventListener::class,
                ],
            ],
        ];

        $this->assertProcessedConfigurationEquals($expectedConfiguration, [
            __DIR__.'/../../fixtures/config/config_multiple_connections.yml',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtension(): ExtensionInterface
    {
        return new InfluxDbExtension();
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }
}
