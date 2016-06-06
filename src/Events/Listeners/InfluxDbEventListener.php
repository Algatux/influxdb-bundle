<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Events\Listeners;

use Algatux\InfluxDbBundle\Events\AbstractDeferredInfluxDbEvent;
use Algatux\InfluxDbBundle\Events\AbstractInfluxDbEvent;
use Algatux\InfluxDbBundle\Events\DeferredUdpEvent;
use Algatux\InfluxDbBundle\Events\UdpEvent;
use InfluxDB\Database;
use InfluxDB\Point;
use Symfony\Component\EventDispatcher\Event;

/**
 * @internal
 */
final class InfluxDbEventListener
{
    const STORAGE_KEY_UDP = 'udp';
    const STORAGE_KEY_HTTP = 'http';

    /**
     * @var string
     */
    private $connection;

    /**
     * @var bool
     */
    private $isDefault;

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
     * @param string   $connection
     * @param bool     $isDefault
     * @param Database $httpDatabase
     * @param Database $udpDatabase
     */
    public function __construct(string $connection, bool $isDefault, Database $httpDatabase, Database $udpDatabase = null)
    {
        $this->connection = $connection;
        $this->isDefault = $isDefault;
        $this->httpDatabase = $httpDatabase;
        $this->udpDatabase = $udpDatabase;
        $this->initStorage();
    }

    /**
     * @param AbstractInfluxDbEvent $event
     * 
     * @return bool
     */
    public function onPointsCollected(AbstractInfluxDbEvent $event): bool
    {
        if (!$this->isConcerned($event)) {
            return false;
        }

        if (!$this->udpDatabase && ($event instanceof UdpEvent || $event instanceof DeferredUdpEvent)) {
            throw new \RuntimeException(
                'No UDP connection available for database "'.$this->httpDatabase->getName().'". '
                .'You must enable it on the configuration to use it.'
            );
        }

        $points = $event->getPoints();
        $precision = $event->getPrecision();

        if ($event instanceof AbstractDeferredInfluxDbEvent) {
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

    /**
     * @param AbstractInfluxDbEvent $event
     * 
     * @return bool
     */
    private function isConcerned(AbstractInfluxDbEvent $event): bool
    {
        return $this->connection === $event->getConnection() || is_null($event->getConnection()) && $this->isDefault;
    }
}
