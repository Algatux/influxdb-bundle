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

    /** @var string */
    private $username;

    /** @var string  */
    private $password;

    /**
     * InfluxDbClientFactory constructor.
     * @param string $host
     * @param string $database
     * @param string $udpPort
     * @param string $httpPort
     * @param string $username
     * @param string $password
     */
    public function __construct($host, $database, $udpPort, $httpPort, $username = '', $password = '')
    {
        $this->host = $host;
        $this->database = $database;
        $this->udpPort = $udpPort;
        $this->httpPort = $httpPort;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return Database
     */
    public function buildUdpClient(): Database
    {
        $client = new Client($this->host,$this->udpPort, $this->username, $this->password);
        $client->setDriver(new UDP($this->host, $this->udpPort));

        return $client->selectDB($this->database);
    }

    /**
     * @return Database
     */
    public function buildHttpClient(): Database
    {
        $client = new Client($this->host,$this->httpPort, $this->username, $this->password);

        return $client->selectDB($this->database);
    }

}
