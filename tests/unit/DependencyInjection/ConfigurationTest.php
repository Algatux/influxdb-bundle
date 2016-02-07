<?php
declare(strict_types=1);
namespace Algatux\InfluxDbBundle\Tests\unit;

use Algatux\InfluxDbBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    public function test_configuration_tree_build()
    {
        $conf = new Configuration();
        $tree = $conf->getConfigTreeBuilder();

        $builtConf = $tree->buildTree();

        $this->assertInstanceOf(ArrayNode::class, $builtConf);
        $this->assertEquals('influx_db',$builtConf->getName());

        $processor = new Processor();

        $conf = $processor->process($builtConf, []);

        $this->assertArrayHasKey('host', $conf);
        $this->assertArrayHasKey('udp_port', $conf);
        $this->assertArrayHasKey('http_port', $conf);
        $this->assertArrayHasKey('database', $conf);
        $this->assertArrayHasKey('use_events', $conf);

        $this->assertEquals('localhost',$conf['host']);
        $this->assertEquals('4444',$conf['udp_port']);
        $this->assertEquals('8086',$conf['http_port']);
        $this->assertEquals('udp',$conf['database']);
        $this->assertEquals(false,$conf['use_events']);
    }
    
}
