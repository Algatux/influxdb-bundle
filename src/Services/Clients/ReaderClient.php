<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Services\Clients;

use Algatux\InfluxDbBundle\Services\Clients\Contracts\ReaderInterface;
use InfluxDB\Database;

@trigger_error(
    'The '.__NAMESPACE__.'\ReaderClient class is deprecated since version 1.1 and will be removed in 2.0.',
    E_USER_DEPRECATED
);

/**
 * Class ReaderClient.
 *
 * @deprecated Since version 1.1, to be removed in 2.0.
 */
class ReaderClient implements ReaderInterface
{
    /** @var Database */
    private $database;

    /**
     * ReaderClient constructor.
     *
     * @param InfluxDbClientFactory $clientFactory
     */
    public function __construct(InfluxDbClientFactory $clientFactory)
    {
        $this->database = $clientFactory->buildHttpClient();
    }
}
