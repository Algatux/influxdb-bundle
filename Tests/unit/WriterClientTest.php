<?php
//declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Tests\unit;

use Algatux\InfluxDbBundle\Services\WriterClient;
use InfluxDB\Driver\Guzzle;
use InfluxDB\Driver\UDP;

class WriterClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param $protocol
     * @param $driver
     *
     * @dataProvider driverProvider
     */
    public function test_setDriver_will_set_the_correct_protocol_driver($protocol,$driver)
    {
        $client = new WriterClient('localhost', '9999');
        $client->setProtocol($protocol);

        $this->assertEquals($driver,$client->getProtocolDriver());
    }

    public function test_setDriver_throw_exception_on_unsupported_driver()
    {
        $client = new WriterClient('localhost', '9999');
        $this->setExpectedException(\LogicException::class);
        $client->setProtocol('http');
    }

    public function driverProvider()
    {
        return [
            ['udp', UDP::class],
            ['tcp', Guzzle::class]
        ];
    }

}
