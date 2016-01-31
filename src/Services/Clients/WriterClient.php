<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\Services\Clients;

use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\WriterInterface;
use InfluxDB\Database;

/**
 * Class WriterClient
 * @package Algatux\InfluxDbBundle\Services\Clients
 */
class WriterClient implements WriterInterface
{

    /** @var Database  */
    private $database;

    /**
     * WriterClient constructor.
     * @param InfluxDbClientFactory $factory
     * @param string $clientType
     */
    public function __construct(InfluxDbClientFactory $factory, $clientType)
    {
        $this->database = $clientType === ClientInterface::HTTP_CLIENT ?
            $factory->buildHttpClient() :
            $factory->buildUdpClient();
    }

    /**
     * @param PointsCollection $points
     * @param string $payload
     * @return bool
     */
    public function write(PointsCollection $points, $payload): bool
    {
        return $this->database->writePoints($points->toArray(), $payload);
    }

}
