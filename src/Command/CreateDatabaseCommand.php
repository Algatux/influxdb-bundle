<?php

declare(strict_types=1);

namespace Yproximite\InfluxDbBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Creates database from command line.
 */
final class CreateDatabaseCommand extends AbstractConnectionCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('yproximite:influx:database:create')
            ->setDescription('Creates the configured database')
            ->addOption('if-not-exists', null, InputOption::VALUE_NONE, 'Don\'t trigger an error, when the database already exists')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->connection->exists() && !$input->getOption('if-not-exists')) {
            $this->io->error('Database "'.$this->connection->getName().'" already exists.');

            return 1;
        }

        if ($this->connection->exists()) {
            $this->io->comment('Database <comment>'.$this->connection->getName().'</comment> already exists. Skipped.');

            return 0;
        }

        $this->connection->create(null, false);
        $this->io->success('Created database "'.$this->connection->getName().'".');

        return 0;
    }
}
