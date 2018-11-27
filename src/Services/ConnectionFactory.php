<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Services;

use InfluxDB\Client;
use InfluxDB\Database;
use InfluxDB\Driver\UDP;

/**
 * Create connections as `InfluxDB\Database` instances.
 *
 * This keeps clients on a property to avoid useless instance duplication.
 *
 * @internal
 */
final class ConnectionFactory
{
    /**
     * @var Client[]
     */
    private $clients = [];

    /**
     * @param string $database
     * @param string $host
     * @param int    $httpPort
     * @param int    $udpPort
     * @param string $user
     * @param string $password
     * @param bool   $udp
     * @param bool   $ssl
     *
     * @return Database
     */
    public function createConnection(
        string $database,
        string $host,
        int $httpPort,
        int $udpPort,
        string $user,
        string $password,
        bool $udp = false,
        bool $ssl = false
    ): Database {
        $protocol = $udp ? 'udp' : 'http';
        // Define the client key to retrieve or create the client instance.
        $clientKey = sprintf('%s.%s.%s', $host, $udpPort, $httpPort);
        if (!empty($user)) {
            $clientKey .= '.'.$user;
        }
        $clientKey .= '.'.$protocol;

        $client = $this->getClientForConfiguration($host, $httpPort, $udpPort, $user, $password, $udp, $ssl, $clientKey);

        return $client->selectDB($database);
    }

    /**
     * @param string $host
     * @param int    $httpPort
     * @param int    $udpPort
     * @param string $user
     * @param string $password
     * @param bool   $udp
     * @param bool   $ssl
     *
     * @return Client
     */
    private function createClient(
        string $host,
        int $httpPort,
        int $udpPort,
        string $user,
        string $password,
        bool $udp = false,
        bool $ssl = false
    ): Client {
        $client = new Client($host, $httpPort, $user, $password, $ssl);

        if ($udp) {
            $client->setDriver(new UDP($client->getHost(), $udpPort));
        }

        return $client;
    }

    /**
     * @param string $host
     * @param int    $httpPort
     * @param int    $udpPort
     * @param string $user
     * @param string $password
     * @param bool   $udp
     * @param bool   $ssl
     * @param string $clientKey
     *
     * @return Client
     */
    private function getClientForConfiguration(
        string $host,
        int $httpPort,
        int $udpPort,
        string $user,
        string $password,
        bool $udp,
        bool $ssl,
        $clientKey
    ): Client {
        if (!array_key_exists($clientKey, $this->clients)) {
            $client = $this->createClient($host, $httpPort, $udpPort, $user, $password, $udp, $ssl);
            $this->clients[$clientKey] = $client;

            return $client;
        }
        $client = $this->clients[$clientKey];

        return $client;
    }
}
