<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Services\Clients\Contracts;

@trigger_error(
    'The '.__NAMESPACE__.'\ClientInterface interface is deprecated since version 1.1 and will be removed in 2.0.',
    E_USER_DEPRECATED
);

/**
 * Interface ClientInterface.
 *
 * @deprecated Since version 1.1, to be removed in 2.0.
 */
interface ClientInterface
{
    const HTTP_CLIENT = 'http';

    const UDP_CLIENT = 'udp';
}
