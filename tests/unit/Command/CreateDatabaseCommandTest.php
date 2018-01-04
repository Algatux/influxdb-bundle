<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Tests\unit\Command;

use Algatux\InfluxDbBundle\Command\CreateDatabaseCommand;
use InfluxDB\Database;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class CreateDatabaseCommandTest extends TestCase
{
    public function test_execute()
    {
        $connection = $this->prophesize(Database::class);
        $connection->exists()->shouldBeCalled();
        $connection->create(null, false)->shouldBeCalled();
        $connection->getName()->shouldBeCalled()->willReturn('test');

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('algatux_influx_db.connection.http')
            ->shouldBeCalled()
            ->willReturn($connection);

        $application = new Application();
        $application->add(new CreateDatabaseCommand());

        $command = $application->find('algatux:influx:database:create');
        $command->setContainer($container->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertContains('Created database "test".', $commandTester->getDisplay());
    }

    public function test_execute_connection_option()
    {
        $connection = $this->prophesize(Database::class);
        $connection->exists()->shouldBeCalled();
        $connection->create(null, false)->shouldBeCalled();
        $connection->getName()->shouldBeCalled()->willReturn('foo');

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('algatux_influx_db.connection.foo.http')
            ->shouldBeCalled()
            ->willReturn($connection);

        $application = new Application();
        $application->add(new CreateDatabaseCommand());

        $command = $application->find('algatux:influx:database:create');
        $command->setContainer($container->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), '--connection' => 'foo']);

        $this->assertContains('Created database "foo".', $commandTester->getDisplay());
    }

    public function test_execute_already_exists()
    {
        $connection = $this->prophesize(Database::class);
        $connection->exists()->shouldBeCalled()->willReturn(true);
        $connection->create(null, false)->shouldNotBeCalled();
        $connection->getName()->shouldBeCalled()->willReturn('test');

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('algatux_influx_db.connection.http')
            ->shouldBeCalled()
            ->willReturn($connection);

        $application = new Application();
        $application->add(new CreateDatabaseCommand());

        $command = $application->find('algatux:influx:database:create');
        $command->setContainer($container->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertContains('Database "test" already exists.', $commandTester->getDisplay());
    }

    public function test_execute_already_exists_skipped()
    {
        $connection = $this->prophesize(Database::class);
        $connection->exists()->shouldBeCalled()->willReturn(true);
        $connection->create(null, false)->shouldNotBeCalled();
        $connection->getName()->shouldBeCalled()->willReturn('test');

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('algatux_influx_db.connection.http')
            ->shouldBeCalled()
            ->willReturn($connection);

        $application = new Application();
        $application->add(new CreateDatabaseCommand());

        $command = $application->find('algatux:influx:database:create');
        $command->setContainer($container->reveal());

        $commandTester = new CommandTester($command);
        $commandTester->execute(
            ['command' => $command->getName(), '--if-not-exists' => true]
        );

        $this->assertContains('Database test already exists. Skipped.', $commandTester->getDisplay());
    }
}
