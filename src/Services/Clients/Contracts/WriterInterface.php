<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Services\Clients\Contracts;

use Algatux\InfluxDbBundle\Model\PointsCollection;

@trigger_error(
    'The '.__NAMESPACE__.'\WriterInterface interface is deprecated since version 1.1 and will be removed in 2.0.',
    E_USER_DEPRECATED
);

/**
 * Interface WriterInterface.
 *
 * @deprecated Since version 1.1, to be removed in 2.0.
 */
interface WriterInterface
{
    /**
     * @param PointsCollection $points
     *
     * @return bool
     */
    public function write(PointsCollection $points): bool;
}
