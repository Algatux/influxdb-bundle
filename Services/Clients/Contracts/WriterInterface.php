<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\Services\Clients\Contracts;

/**
 * Interface WriterInterface
 * @package Algatux\InfluxDbBundle\Services\Clients\Contracts
 */
interface WriterInterface
{

    /**
     * @param array $parameters
     * @param string $payload
     * @return bool
     */
    public function write(array $parameters, string $payload): bool;

}