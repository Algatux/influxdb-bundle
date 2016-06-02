<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\unit\Services\Clients;

use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\WriterInterface;
use Algatux\InfluxDbBundle\Services\Clients\InfluxDbClientFactory;
use Algatux\InfluxDbBundle\Services\Clients\WriterClient;
use InfluxDB\Database;
use Prophecy\Argument;

class WriterClientTest extends \PHPUnit_Framework_TestCase
{
    public function test_http_client_construction()
    {
        $factory = $this->prophesize(InfluxDbClientFactory::class);
        $factory->buildHttpClient()->shouldBeCalledTimes(1);

        $writer = new WriterClient($factory->reveal(), ClientInterface::HTTP_CLIENT);

        $this->assertInstanceOf(WriterInterface::class, $writer);
    }

    public function test_udp_client_construction()
    {
        $factory = $this->prophesize(InfluxDbClientFactory::class);
        $factory->buildUdpClient()->shouldBeCalledTimes(1);

        $writer = new WriterClient($factory->reveal(), ClientInterface::UDP_CLIENT);

        $this->assertInstanceOf(WriterInterface::class, $writer);
    }

    public function test_write()
    {
        $idbDatabase = $this->prophesize(Database::class);
        $idbDatabase->writePoints(Argument::type('array'), Argument::type('string'))
            ->shouldBeCalledTimes(1)
            ->willReturn(true);

        $factory = $this->prophesize(InfluxDbClientFactory::class);
        $factory->buildHttpClient()->shouldBeCalledTimes(1)->willReturn($idbDatabase->reveal());

        $writer = new WriterClient($factory->reveal(), ClientInterface::HTTP_CLIENT);
        $writer->write(new PointsCollection(), 'test');
    }
}
