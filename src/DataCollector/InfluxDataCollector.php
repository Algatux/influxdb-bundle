<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

final class InfluxDataCollector extends DataCollector
{
    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'queries' => [
                'SHOW MEASUREMENTS',
                'SELECT * FROM test',
            ],
        ];
    }

    /**
     * @return int
     */
    public function getQueryCount(): int
    {
        return count($this->data['queries']);
    }

    /**
     * @return float
     */
    public function getTime(): float
    {
        return 0.042;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'influx';
    }
}
