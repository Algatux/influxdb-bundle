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

    /** @var string  */
    private $database;

    /**
     * InfluxDbClientFactory constructor.
     * @param string $host
     * @param string $database
     * @param string $udpPort
     * @param string $httpPort
     */
    public function __construct($host, $database, $udpPort, $httpPort)
    {
        $this->host = $host;
        $this->database = $database;
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
        $client->selectDB($this->database);

        return $client;
    }

    /**
     * @return Client
     */
    public function buildHttpClient()
    {
        $client = new Client($this->host,$this->udpPort);
        $client->selectDB($this->database);

        return $client;
    }

}
