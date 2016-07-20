<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Services;

use Algatux\InfluxDbBundle\Exception\ConnectionNotFoundException;
use InfluxDB\Database;

/**
 * Registry of all Database instances.
 */
final class ConnectionRegistry
{
    /**
     * @var Database[]
     */
    private $connections = [];

    /**
     * @var string
     */
    private $defaultConnectionName;

    /**
     * @param string $defaultConnectionName
     */
    public function __construct($defaultConnectionName)
    {
        $this->defaultConnectionName = $defaultConnectionName;
    }

    /**
     * @param string   $name
     * @param string   $protocol
     * @param Database $connection
     */
    public function addConnection(string $name, string $protocol, Database $connection)
    {
        if (!isset($this->connections[$name])) {
            $this->connections[$name] = [];
        }

        $this->connections[$name][$protocol] = $connection;
    }

    /**
     * @param string $name
     *
     * @return Database
     */
    public function getHttpConnection(string $name)
    {
        return $this->getConnection($name, 'http');
    }

    /**
     * @param string $name
     *
     * @return Database
     */
    public function getUdpConnection(string $name)
    {
        return $this->getConnection($name, 'udp');
    }

    /**
     * @return Database
     */
    public function getDefaultHttpConnection()
    {
        return $this->getConnection($this->defaultConnectionName, 'http');
    }

    /**
     * @return Database
     */
    public function getDefaultUdpConnection()
    {
        return $this->getConnection($this->defaultConnectionName, 'udp');
    }

    /**
     * @param string $name
     * @param string $protocol
     *
     * @return Database
     *
     * @throws ConnectionNotFoundException
     */
    private function getConnection(string $name, string $protocol)
    {
        if (!isset($this->connections[$name][$protocol])) {
            throw new ConnectionNotFoundException($name, $protocol);
        }

        return $this->connections[$name][$protocol];
    }
}
