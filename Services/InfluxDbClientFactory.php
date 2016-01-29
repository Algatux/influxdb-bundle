<?php
declare(strict_types = 1);

namespace Algatux\InfluxDbBundle\Services;

use InfluxDB\Client;

class InfluxDbClientFactory
{

    /**
     * @return Client
     */
    public function buildClient(): Client
    {
        return new Client('localhost');
    }
}