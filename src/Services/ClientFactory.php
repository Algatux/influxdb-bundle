<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Services;

use InfluxDB\Client;

/**
 * Factory to build InfluxDB\Client instances.
 */
final class ClientFactory
{
    /**
     * @param string $host
     * @param string $httpPort
     * @param string $udpPort
     * @param string $user
     * @param string $password
     * @param bool   $udp
     *
     * @return Client
     */
    public static function createClient(string $host, string $httpPort, string $udpPort, string $user, string $password, bool $udp = false)
    {
        $client = new Client($host, $httpPort, $user, $password);

        if ($udp) {
            $client->setDriver(new \InfluxDB\Driver\UDP($client->getHost(), $udpPort));
        }

        return $client;
    }
}
