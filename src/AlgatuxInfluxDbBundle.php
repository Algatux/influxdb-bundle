<?php

namespace Algatux\InfluxDbBundle;

use Algatux\InfluxDbBundle\DependencyInjection\AlgatuxInfluxDbExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class AlgatuxInfluxDbBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new AlgatuxInfluxDbExtension();
    }
}
