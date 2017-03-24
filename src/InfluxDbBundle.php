<?php

namespace Algatux\InfluxDbBundle;

use Algatux\InfluxDbBundle\DependencyInjection\InfluxDbExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class InfluxDbBundle.
 *
 * @deprecated please use AlgatuxInfluxDbBundle instead
 */
final class InfluxDbBundle extends Bundle
{
    /**
     * @return InfluxDbExtension
     */
    public function getContainerExtension()
    {
        return new InfluxDbExtension();
    }
}
