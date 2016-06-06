<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Tests\unit;

use Algatux\InfluxDbBundle\DependencyInjection\Configuration;
use Algatux\InfluxDbBundle\DependencyInjection\InfluxDbExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

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
                    'udp_port' => '4444',
                    'http_port' => '8086',
                    'username' => '',
                    'password' => '',
                ],
            ],
        ];

        $this->assertProcessedConfigurationEquals($expectedConfiguration, [
            __DIR__.'/../../fixtures/config/config_minimal.yml',
        ]);
    }

    public function test_full_configuration_process()
    {
        $expectedConfiguration = [
            'default_connection' => 'default',
            'connections' => [
                'default' => [
                    'host' => 'localhost',
                    'database' => 'telegraf',
                    'udp_port' => '1337',
                    'http_port' => '42',
                    'username' => 'foo',
                    'password' => 'bar',
                ],
            ],
        ];

        $this->assertProcessedConfigurationEquals($expectedConfiguration, [
            __DIR__.'/../../fixtures/config/config_full.yml',
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
                    'udp_port' => 4444,
                    'http_port' => 8086,
                    'username' => 'foo',
                    'password' => 'bar',
                ],
                'test' => [
                    'database' => 'test',
                    'host' => 'localhost',
                    'udp_port' => 4444,
                    'http_port' => 8086,
                    'username' => 'foo',
                    'password' => 'bar',
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
    protected function getContainerExtension()
    {
        return new InfluxDbExtension();
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }
}
