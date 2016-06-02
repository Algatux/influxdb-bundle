<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Services\Clients;

use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\WriterInterface;
use InfluxDB\Database;

@trigger_error(
    'The '.__NAMESPACE__.'\WriterClient class is deprecated since version 1.1 and will be removed in 2.0.',
    E_USER_DEPRECATED
);

/**
 * Class WriterClient.
 *
 * @deprecated Since version 1.1, to be removed in 2.0.
 */
class WriterClient implements WriterInterface
{
    /** @var Database */
    private $database;

    /**
     * WriterClient constructor.
     *
     * @param InfluxDbClientFactory $factory
     * @param string                $clientType
     */
    public function __construct(InfluxDbClientFactory $factory, string $clientType)
    {
        $this->database = $clientType === ClientInterface::HTTP_CLIENT ?
            $factory->buildHttpClient() :
            $factory->buildUdpClient();
    }

    /**
     * @param PointsCollection $points
     *
     * @return bool
     */
    public function write(PointsCollection $points): bool
    {
        return $this->database->writePoints($points->toArray(), $points->getPrecision());
    }
}
