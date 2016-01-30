<?php
//declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Services\Clients\Contracts;

/**
 * Interface ClientInterface
 * @package Algatux\InfluxDbBundle\Clients
 */
interface ClientInterface
{

    const HTTP_CLIENT = 'http';

    const UDP_CLIENT = 'udp';

}
