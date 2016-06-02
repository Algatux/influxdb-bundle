<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Services\Clients\Contracts;

/**
 * Interface ClientInterface.
 */
interface ClientInterface
{
    const HTTP_CLIENT = 'http';

    const UDP_CLIENT = 'udp';
}
