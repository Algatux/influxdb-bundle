<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\Events\Listeners;
use Algatux\InfluxDbBundle\Events\InfluxDbEvent;
use Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface;
use Algatux\InfluxDbBundle\Services\Clients\WriterClient;

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

    /**
     * InfluxDbEventListener constructor.
     * @param WriterClient $httpWriter
     * @param WriterClient $udpWriter
     */
    public function __construct(WriterClient $httpWriter, WriterClient $udpWriter)
    {
        $this->httpWriter = $httpWriter;
        $this->udpWriter = $udpWriter;
    }

    public function onInfluxDbEventDispatched(InfluxDbEvent $event): bool
    {

        $points = $event->getPoints();

        if ($event->getWriteMode() === ClientInterface::UDP_CLIENT) {
            $this->udpWriter->write($points);
        }

        if ($event->getWriteMode() === ClientInterface::HTTP_CLIENT) {
            $this->httpWriter->write($points);
        }

        return true;

    }

}
