<?php

namespace Algatux\InfluxDbBundle\unit;

use Algatux\InfluxDbBundle\AlgatuxInfluxDbBundle;
use Algatux\InfluxDbBundle\DependencyInjection\InfluxDbExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractContainerBuilderTestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AlgatuxInfluxDbBundleTest extends AbstractContainerBuilderTestCase
{
    /**
     * @var AlgatuxInfluxDbBundle
     */
    protected $bundle;

    protected function setUp()
    {
        parent::setUp();

        $this->bundle = new AlgatuxInfluxDbBundle();
    }

    public function test_build()
    {
        $this->bundle->build($this->container);
    }

    public function test_get_container_extension()
    {
        $this->assertInstanceOf(Bundle::class, $this->bundle);
        $this->assertInstanceOf(InfluxDbExtension::class, $this->bundle->getContainerExtension());
    }
}
