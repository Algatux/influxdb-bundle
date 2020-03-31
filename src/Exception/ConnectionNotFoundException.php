<?php

declare(strict_types=1);

namespace Yproximite\InfluxDbBundle\Exception;

/**
 * Thrown when an InfluxDB connection can't be retrieved from the registry.
 */
final class ConnectionNotFoundException extends \RuntimeException
{
    /**
     * @param string          $connectionName
     * @param string          $protocol
     * @param \Exception|null $previous
     */
    public function __construct(string $connectionName, string $protocol, \Exception $previous = null)
    {
        parent::__construct('Connection "'.$connectionName.'" for '.$protocol.' protocol does not exist.', 0, $previous);
    }
}
