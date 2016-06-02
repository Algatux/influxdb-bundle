<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\unit\Services\Clients;

use Algatux\InfluxDbBundle\Services\Clients\Contracts\ReaderInterface;
use Algatux\InfluxDbBundle\Services\Clients\InfluxDbClientFactory;
use Algatux\InfluxDbBundle\Services\Clients\ReaderClient;

class ReaderClientTest extends \PHPUnit_Framework_TestCase
{
    public function test_http_client_construction()
    {
        $factory = $this->prophesize(InfluxDbClientFactory::class);
        $factory->buildHttpClient()->shouldBeCalledTimes(1);

        $reader = new ReaderClient($factory->reveal());

        $this->assertInstanceOf(ReaderInterface::class, $reader);
    }
}
