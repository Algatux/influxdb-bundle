<?php
declare(strict_types = 1);
namespace Algatux\InfluxDbBundle\Services\Clients;

use InfluxDB\Client;
use InfluxDB\Database;
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
     * @return Database
     */
    public function buildUdpClient(): Database
    {
        $client = new Client($this->host,$this->udpPort);
        $client->setDriver(new UDP($this->host, $this->udpPort));

        return $client->selectDB($this->database);
    }

    /**
     * @return Database
     */
    public function buildHttpClient(): Database
    {
        $client = new Client($this->host,$this->httpPort);

        return $client->selectDB($this->database);
    }

}
