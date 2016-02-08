<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\Events\Listeners;
use Algatux\InfluxDbBundle\Events\DeferredInfluxDbEvent;
use Algatux\InfluxDbBundle\Events\InfluxDbEvent;
use Algatux\InfluxDbBundle\Model\PointsCollection;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface;
use Algatux\InfluxDbBundle\Services\Clients\WriterClient;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class InfluxDbEventListener
 * @package Algatux\InfluxDbBundle\Events\Listeners
 */
class InfluxDbEventListener
{

    /** @var WriterClient  */
    private $httpWriter;

    /** @var WriterClient  */
    private $udpWriter;

    /** @var \SplQueue  */
    private $pointCollections;

    /**
     * InfluxDbEventListener constructor.
     * @param WriterClient $httpWriter
     * @param WriterClient $udpWriter
     */
    public function __construct(WriterClient $httpWriter, WriterClient $udpWriter)
    {
        $this->httpWriter = $httpWriter;
        $this->udpWriter = $udpWriter;
        $this->pointCollections = null;
    }

    public function onPointsCollected(InfluxDbEvent $event): bool
    {
        if ($event->isPropagationStopped()) {
            return false;
        }

        $points = $event->getPoints();

        if ($event instanceof DeferredInfluxDbEvent) {

            $this->initCollection($event->getWriteMode(),$points->getPrecision());

            /** @var PointsCollection $actualCollection */
            $actualCollection = $this->pointCollections[$event->getWriteMode()][$points->getPrecision()];

            $mergedCollection = new PointsCollection(array_merge(
                $actualCollection->toArray(),
                $points->toArray()
            ), $points->getPrecision());

            $this->pointCollections[$event->getWriteMode()][$points->getPrecision()] = $mergedCollection;

            return true;
        }

        $this->writePoints($event->getWriteMode(), $points);

        return true;
    }

    /**
     * @param Event $event
     * @return bool
     */
    public function onKernelTerminate(Event $event): bool
    {
        if ($event->isPropagationStopped()) {
            return false;
        }

        if (! empty($this->pointCollections)) {

            foreach ($this->pointCollections as $writeMode => $precisionGroup) {
                /** @var PointsCollection $pointsCollection */
                foreach ($precisionGroup as $precision => $pointsCollection) {
                    $this->writePoints($writeMode, $pointsCollection);
                }
            }

        }

        return true;
    }

    /**
     * @param string $writemode
     * @param $points
     */
    private function writePoints(string $writemode, $points)
    {
        if ($writemode === ClientInterface::UDP_CLIENT) {
            $this->udpWriter->write($points);
        }

        if ($writemode === ClientInterface::HTTP_CLIENT) {
            $this->httpWriter->write($points);
        }
    }

    /**
     * @param string $getWriteMode
     * @param string $getPrecision
     */
    private function initCollection(string $getWriteMode, string $getPrecision)
    {
        if (! isset($this->pointCollections[$getWriteMode])) {
            $this->pointCollections[$getWriteMode] = null;
        }

        if (!isset($this->pointCollections[$getWriteMode][$getPrecision])) {
            $this->pointCollections[$getWriteMode][$getPrecision] = new PointsCollection([], $getPrecision);
        }
    }

}
