<?php

namespace Algatux\InfluxDbBundle;

use Algatux\InfluxDbBundle\DependencyInjection\AlgatuxInfluxDbExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class AlgatuxInfluxDbBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new AlgatuxInfluxDbExtension();
    }
}
