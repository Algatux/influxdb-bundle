<?php declare(strict_types = 1);

namespace Algatux\InfluxDbBundle\Events;

use Algatux\InfluxDbBundle\DataCollector\InfluxQuery;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class QueryEvent.
 */
class QueryEvent extends Event
{
    /** @var  InfluxQuery */
    private $query;
    /** @var array */
    private $arguments;

    /**
     * @param InfluxQuery $query
     * @param array       $arguments
     */
    public function construct(InfluxQuery $query, array $arguments = [])
    {
        $this->query = $query;
        $this->arguments = $arguments;
    }

    /**
     * @return InfluxQuery
     */
    public function getQuery(): InfluxQuery
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
