<?php

//declare(strict_types=1);

namespace Algatux\InfluxDbBundle\unit;

use Algatux\InfluxDbBundle\DependencyInjection\InfluxDbExtension;
use Algatux\InfluxDbBundle\InfluxDbBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class InfluxDbBundleTest extends \PHPUnit_Framework_TestCase
{
    public function test_get_container_extension()
    {
        $bundle = new InfluxDbBundle();

        $extension = $bundle->getContainerExtension();

        $this->assertInstanceOf(Bundle::class, $bundle);
        $this->assertInstanceOf(InfluxDbExtension::class, $extension);
    }
}
