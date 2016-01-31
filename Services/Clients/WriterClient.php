<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\Services\Clients;

use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\WriterInterface;
use InfluxDB\Client as InfluxDbClient;

/**
 * Class WriterClient
 * @package Algatux\InfluxDbBundle\Services\Clients
 */
class WriterClient implements WriterInterface
{

    /** @var InfluxDbClient  */
    private $client;

    /**
     * WriterClient constructor.
     * @param InfluxDbClientFactory $factory
     * @param string $clientType
     */
    public function __construct(InfluxDbClientFactory $factory, $clientType)
    {
        $this->client = $clientType === ClientInterface::HTTP_CLIENT ?
            $factory->buildHttpClient() :
            $factory->buildUdpClient();
    }

    /**
     * @param PointsCollection $points
     * @param string $payload
     * @return bool
     */
    public function write(PointsCollection $points, string $payload): bool
    {
        return $this->client->write($points->toArray(), $payload);
    }

}
