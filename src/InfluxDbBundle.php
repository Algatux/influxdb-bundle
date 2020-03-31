<?php

namespace Yproximite\InfluxDbBundle;

use Yproximite\InfluxDbBundle\DependencyInjection\InfluxDbExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class InfluxDbBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new InfluxDbExtension();
    }
}
