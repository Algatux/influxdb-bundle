<?php

//declare(strict_types=1);

namespace Algatux\InfluxDbBundle\unit;

use Algatux\InfluxDbBundle\DependencyInjection\AlgatuxInfluxDbExtension;
use Algatux\InfluxDbBundle\AlgatuxInfluxDbBundle;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractContainerBuilderTestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AlgatuxInfluxDbBundleTest extends AbstractContainerBuilderTestCase
{
    /**
     * @var AlgatuxInfluxDbBundle
     */
    protected $bundle;

    protected function setUp(): void
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
        $this->assertInstanceOf(AlgatuxInfluxDbExtension::class, $this->bundle->getContainerExtension());
    }
}
