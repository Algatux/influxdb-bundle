<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Services;

use InfluxDB\Client;
use InfluxDB\Driver\UDP;

/**
 * Factory to build InfluxDB\Client instances.
 */
final class ClientFactory
{
    /**
     * @param string $host
     * @param int    $httpPort
     * @param int    $udpPort
     * @param string $user
     * @param string $password
     * @param bool   $udp
     *
     * @return Client
     */
    public static function createClient(string $host, int $httpPort, int $udpPort, string $user, string $password, bool $udp = false)
    {
        $client = new Client($host, $httpPort, $user, $password);

        if ($udp) {
            $client->setDriver(new UDP($client->getHost(), $udpPort));
        }

        return $client;
    }
}
