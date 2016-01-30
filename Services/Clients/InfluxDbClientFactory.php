<?php
declare(strict_types = 1);
namespace Algatux\InfluxDbBundle\Services\Clients;

use InfluxDB\Client;
use InfluxDB\Driver\UDP;

/**
 * Class InfluxDbClientFactory
 * @package Algatux\InfluxDbBundle\Services\Clients
 */
class InfluxDbClientFactory
{

    /** @var string  */
    private $host;

    /** @var string  */
    private $udpPort;

    /** @var string  */
    private $httpPort;

    /**
     * InfluxDbClientFactory constructor.
     * @param string $host
     * @param string $udpPort
     * @param string $httpPort
     */
    public function __construct($host, $udpPort, $httpPort)
    {
        $this->host = $host;
        $this->udpPort = $udpPort;
        $this->httpPort = $httpPort;
    }

    /**
     * @return Client
     */
    public function buildUdpClient()
    {
        $client = new Client($this->host,$this->udpPort);
        $client->setDriver(new UDP($this->host, $this->udpPort));

        return $client;
    }

    /**
     * @return Client
     */
    public function buildHttpClient()
    {
        return new Client($this->host,$this->udpPort);
    }

}
