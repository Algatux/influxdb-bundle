<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\Services\Clients;

use Algatux\InfluxDbBundle\Services\Clients\Contracts\ReaderInterface;
use InfluxDB\Client as InfluxDbClient;

class ReaderClient implements ReaderInterface
{

    /** @var InfluxDbClient  */
    private $client;

    public function __construct(InfluxDbClientFactory $clientFactory)
    {
        $this->client = $clientFactory->buildHttpClient();
    }

}
