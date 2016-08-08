<?php

namespace Algatux\InfluxDbBundle;

use Algatux\InfluxDbBundle\DependencyInjection\InfluxDbExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AlgatuxInfluxDbBundle.
 */
final class AlgatuxInfluxDbBundle extends Bundle
{
    /**
     * @return InfluxDbExtension
     */
    public function getContainerExtension()
    {
        return new InfluxDbExtension();
    }
}
