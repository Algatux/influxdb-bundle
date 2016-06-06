<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Tests\unit\Command;

use Algatux\InfluxDbBundle\Command\DropDatabaseCommand;
use InfluxDB\Database;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class DropDatabaseCommandTest extends \PHPUnit_Framework_TestCase
{
    public function test_execute()
    {
        $connection = $this->prophesize(Database::class);
        $connection->exists()->shouldBeCalled()->willReturn(true);
        $connection->drop()->shouldBeCalled();
        $connection->getName()->shouldBeCalled()->willReturn('test');

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('algatux_influx_db.connection.http')
            ->shouldBeCalled()
            ->willReturn($connection);

        $application = new Application();
        $application->add(new DropDatabaseCommand());

        $command = $application->find('algatux:influx:database:drop');
        $command->setContainer($container->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), '--force' => true]);

        $this->assertContains('Dropped database "test".', $commandTester->getDisplay());
    }

    public function test_execute_without_force()
    {
        $connection = $this->prophesize(Database::class);
        $connection->exists()->shouldNotBeCalled();
        $connection->drop()->shouldNotBeCalled();
        $connection->getName()->shouldBeCalled()->willReturn('test');

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('algatux_influx_db.connection.http')
            ->shouldBeCalled()
            ->willReturn($connection);

        $application = new Application();
        $application->add(new DropDatabaseCommand());

        $command = $application->find('algatux:influx:database:drop');
        $command->setContainer($container->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertContains('Please run the operation with --force to execute.', $commandTester->getDisplay());
    }

    public function test_execute_connection_option()
    {
        $connection = $this->prophesize(Database::class);
        $connection->exists()->shouldBeCalled()->willReturn(true);
        $connection->drop()->shouldBeCalled();
        $connection->getName()->shouldBeCalled()->willReturn('foo');

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('algatux_influx_db.connection.foo.http')
            ->shouldBeCalled()
            ->willReturn($connection);

        $application = new Application();
        $application->add(new DropDatabaseCommand());

        $command = $application->find('algatux:influx:database:drop');
        $command->setContainer($container->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), '--force' => true, '--connection' => 'foo']);

        $this->assertContains('Dropped database "foo".', $commandTester->getDisplay());
    }

    public function test_execute_does_not_exist()
    {
        $connection = $this->prophesize(Database::class);
        $connection->exists()->shouldBeCalled()->willReturn(false);
        $connection->drop()->shouldNotBeCalled();
        $connection->getName()->shouldBeCalled()->willReturn('test');

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('algatux_influx_db.connection.http')
            ->shouldBeCalled()
            ->willReturn($connection);

        $application = new Application();
        $application->add(new DropDatabaseCommand());

        $command = $application->find('algatux:influx:database:drop');
        $command->setContainer($container->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), '--force' => true]);

        $this->assertContains('Database "test" does not exist.', $commandTester->getDisplay());
    }

    public function test_execute_does_not_exist_skipped()
    {
        $connection = $this->prophesize(Database::class);
        $connection->exists()->shouldBeCalled()->willReturn(false);
        $connection->drop()->shouldNotBeCalled();
        $connection->getName()->shouldBeCalled()->willReturn('test');

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('algatux_influx_db.connection.http')
            ->shouldBeCalled()
            ->willReturn($connection);

        $application = new Application();
        $application->add(new DropDatabaseCommand());

        $command = $application->find('algatux:influx:database:drop');
        $command->setContainer($container->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute(
            ['command' => $command->getName(), '--force' => true, '--if-exists' => true]
        );

        $this->assertContains('Database test does not exist. Skipped.', $commandTester->getDisplay());
    }
}
