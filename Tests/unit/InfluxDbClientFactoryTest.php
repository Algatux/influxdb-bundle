<?php
//declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Tests\unit;

use Algatux\InfluxDbBundle\Services\InfluxDbClientFactory;
use InfluxDB\Client;

class InfluxDbClientFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function test_client_factory_test_exists()
    {
        $factory = new InfluxDbClientFactory();
        $this->assertInstanceOf(InfluxDbClientFactory::class, $factory);
    }

    public function test_build_client_returns_a_valid_client()
    {
        $factory = new InfluxDbClientFactory();
        $client = $factory->buildClient();

        $this->assertInstanceOf(Client::class, $client);
    }

}
