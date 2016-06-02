<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Events\Listeners;

use Algatux\InfluxDbBundle\Events\DeferredInfluxDbEvent;
use Algatux\InfluxDbBundle\Events\InfluxDbEvent;
use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface;
use Algatux\InfluxDbBundle\Services\Clients\WriterClient;
use Algatux\InfluxDbBundle\Services\PointsCollectionStorage;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class InfluxDbEventListener.
 */
class InfluxDbEventListener
{
    /** @var WriterClient */
    private $httpWriter;

    /** @var WriterClient */
    private $udpWriter;

    /** @var PointsCollectionStorage */
    private $collectionStorage;

    /**
     * InfluxDbEventListener constructor.
     *
     * @param WriterClient            $httpWriter
     * @param WriterClient            $udpWriter
     * @param PointsCollectionStorage $collectionStorage
     */
    public function __construct(
        WriterClient $httpWriter,
        WriterClient $udpWriter,
        PointsCollectionStorage $collectionStorage
    ) {
        $this->httpWriter = $httpWriter;
        $this->udpWriter = $udpWriter;
        $this->collectionStorage = $collectionStorage;
    }

    public function onPointsCollected(InfluxDbEvent $event): bool
    {
        $points = $event->getPoints();

        if ($event instanceof DeferredInfluxDbEvent) {
            $this->collectionStorage->storeCollection($points, $event->getWriteMode());

            return true;
        }

        $this->writePoints($event->getWriteMode(), $points);

        return true;
    }

    /**
     * @param Event $event
     *
     * @return bool
     */
    public function onKernelTerminate(Event $event): bool
    {
        $collections = $this->collectionStorage->getStoredCollections();

        foreach ($collections as $writeMode => $precisionGroup) {
            /** @var PointsCollection $pointsCollection */
            foreach ($precisionGroup as $precision => $pointsCollection) {
                $this->writePoints($writeMode, $pointsCollection);
            }
        }

        return true;
    }

    /**
     * @param string           $writemode
     * @param PointsCollection $points
     */
    private function writePoints(string $writemode, PointsCollection $points)
    {
        if ($writemode === ClientInterface::UDP_CLIENT) {
            $this->udpWriter->write($points);
        }

        if ($writemode === ClientInterface::HTTP_CLIENT) {
            $this->httpWriter->write($points);
        }
    }
}
