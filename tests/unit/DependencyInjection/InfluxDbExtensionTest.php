<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\unit\DependencyInjection;

use Algatux\InfluxDbBundle\DependencyInjection\InfluxDbExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class InfluxDbExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function test_load()
    {

        $extension = new InfluxDbExtension();
        $config = $extension->load([],new ContainerBuilder());

        $this->assertNotEmpty($config);
    }

}
