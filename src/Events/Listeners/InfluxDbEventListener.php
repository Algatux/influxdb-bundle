<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Events\Listeners;

use Algatux\InfluxDbBundle\Events\DeferredInfluxDbEvent;
use Algatux\InfluxDbBundle\Events\DeferredUdpEvent;
use Algatux\InfluxDbBundle\Events\InfluxDbEvent;
use Algatux\InfluxDbBundle\Events\UdpEvent;
use InfluxDB\Database;
use InfluxDB\Point;
use Symfony\Component\EventDispatcher\Event;

/**
 * @internal
 */
class InfluxDbEventListener
{
    const STORAGE_KEY_UDP = 'udp';
    const STORAGE_KEY_HTTP = 'http';

    /**
     * @var Database
     */
    private $httpDatabase;

    /**
     * @var Database
     */
    private $udpDatabase;

    /**
     * @var array
     */
    private $storage;

    /**
     * @param Database $httpDatabase
     * @param Database $udpDatabase
     */
    public function __construct(
        Database $httpDatabase,
        Database $udpDatabase
    ) {
        $this->httpDatabase = $httpDatabase;
        $this->udpDatabase = $udpDatabase;
        $this->initStorage();
    }

    public function onPointsCollected(InfluxDbEvent $event): bool
    {
        $points = $event->getPoints();
        $precision = $event->getPrecision();

        if ($event instanceof DeferredInfluxDbEvent) {
            $typeKey = $event instanceof DeferredUdpEvent ? static::STORAGE_KEY_UDP : static::STORAGE_KEY_HTTP;
            $this->addPointsToStorage($typeKey, $precision, $points);

            return true;
        }

        if ($event instanceof UdpEvent) {
            $this->writeUdpPoints($points, $precision);

            return true;
        }

        $this->writeHttpPoints($points, $precision);

        return true;
    }

    /**
     * @param Event $event
     *
     * @return bool
     */
    public function onKernelTerminate(Event $event): bool
    {
        foreach ($this->storage[static::STORAGE_KEY_UDP] as $precision => $points) {
            $this->writeUdpPoints($points, $precision);
        }

        foreach ($this->storage[static::STORAGE_KEY_HTTP] as $precision => $points) {
            $this->writeHttpPoints($points, $precision);
        }

        // Reset the storage after writing points.
        $this->initStorage();

        return true;
    }

    private function initStorage()
    {
        $this->storage = [
            static::STORAGE_KEY_UDP => [],
            static::STORAGE_KEY_HTTP => [],
        ];
    }

    /**
     * @param Point[] $points
     * @param string  $precision
     */
    private function writeUdpPoints(array $points, string $precision)
    {
        $this->udpDatabase->writePoints($points, $precision);
    }

    /**
     * @param Point[] $points
     * @param string  $precision
     */
    private function writeHttpPoints(array $points, string $precision)
    {
        $this->httpDatabase->writePoints($points, $precision);
    }

    /**
     * @param string $typeKey
     * @param string $precision
     * @param array  $points
     */
    private function addPointsToStorage(string $typeKey, string $precision, array $points)
    {
        if (array_key_exists($precision, $this->storage[$typeKey])) {
            $this->storage[$typeKey][$precision] = array_merge($this->storage[$typeKey][$precision], $points);

            return;
        }

        $this->storage[$typeKey][$precision] = $points;
    }
}
