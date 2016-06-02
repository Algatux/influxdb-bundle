<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\unit\DependencyInjection;

use Algatux\InfluxDbBundle\DependencyInjection\InfluxDbExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class InfluxDbExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function test_load()
    {
        $confTest = [
            'influx_db' => [
                'host' => 'localhost',
                'database' => 'udp',
                'udp_port' => '4444',
                'http_port' => '8086',
                'use_events' => true,
            ],
        ];

        $extension = new InfluxDbExtension();
        $config = $extension->load($confTest, new ContainerBuilder());

        $this->assertNotEmpty($config);
    }
}
