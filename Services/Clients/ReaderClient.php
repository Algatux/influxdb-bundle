<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\Services\Clients;

use Algatux\InfluxDbBundle\Services\Clients\Contracts\ReaderInterface;
use InfluxDB\Database;

/**
 * Class ReaderClient
 * @package Algatux\InfluxDbBundle\Services\Clients
 */
class ReaderClient implements ReaderInterface
{

    /** @var Database  */
    private $database;

    /**
     * ReaderClient constructor.
     * @param InfluxDbClientFactory $clientFactory
     */
    public function __construct(InfluxDbClientFactory $clientFactory)
    {
        $this->database = $clientFactory->buildHttpClient();
    }

}
