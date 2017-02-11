<?php declare(strict_types = 1);

namespace Algatux\InfluxDbBundle\DataCollector;

/**
 * Class InfluxQuery.
 */
final class InfluxQuery
{
    private $query;

    private $params;

    /**
     * InfluxQuery constructor.
     */
    public function __construct()
    {
        $this->query = '';
        $this->params = [];
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @param string $query
     */
    public function setQuery(string $query)
    {
        $this->query = $query;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }
}
