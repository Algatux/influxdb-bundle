<?php
//declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Services;

use Algatux\InfluxDbBundle\Contracts\WriterInterface;
use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Model\Protocol;
use InfluxDB\Client;
use InfluxDB\Driver\UDP;

class WriterClient implements WriterInterface
{

    /** @var Client  */
    private $client;

    /** @var string  */
    private $port;

    /** @var array  */
    protected static $supportedProtocols = [
        Protocol::UDP,
        Protocol::TCP,
    ];

    /**
     * WriterClient constructor.
     * @param string $host
     * @param string $port
     */
    public function __construct(string $host, string $port)
    {
        $this->port = $port;
        $this->client = new Client($host, $port);
    }

    /**
     * @param string $protocol*
     */
    public function setProtocol(string $protocol)
    {
        $this->checkProtocolSupport($protocol);

        if ($protocol === Protocol::UDP) {
            $this->client->setDriver(new UDP($this->client->getHost(), $this->port));
        }
    }

    /**
     * @return string
     */
    public function getProtocolDriver(): string
    {
        return get_class($this->client->getDriver());
    }

    /**
     * @inheritdoc
     */
    public function write(PointsCollection $collection, string $payload): bool
    {
        return $this->client->write($collection->toArray(),$payload);
    }

    /**
     * @param string $protocol
     */
    private function checkProtocolSupport(string $protocol)
    {
        if (!in_array($protocol, self::$supportedProtocols)) {
            throw new \LogicException(
                sprintf(
                    'Protocol %s is not supported, please provide one of theese [ \'%s\' ]',
                    $protocol,
                    implode('\', \'', self::$supportedProtocols)
                )
            );
        }
    }

}
