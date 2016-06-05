<?php

declare(strict_types=1);

namespace Algatux\InfluxDbBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Drops database from command line.
 */
final class DropDatabaseCommand extends AbstractConnectionCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('algatux:influx:database:drop')
            ->setDescription('Drops the configured database')
            ->addOption('if-exists', null, InputOption::VALUE_NONE, 'Don\'t trigger an error, when the database doesn\'t exist')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Set this parameter to execute this action')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('force')) {
            $this->io->error(sprintf(<<<'EOF'
ATTENTION: This operation should not be executed in a production environment.
Would drop the database named "%s".
Please run the operation with --force to execute.
All data will be lost!
EOF
            , $this->connection->getName()));

            return 1;
        }

        if (!$this->connection->exists() && !$input->getOption('if-exists')) {
            $this->io->error('Database "'.$this->connection->getName().'" does not exist.');

            return 1;
        }

        if (!$this->connection->exists()) {
            $this->io->comment('Database <comment>'.$this->connection->getName().'</comment> does not exist. Skipped.');

            return 0;
        }

        $this->connection->drop();
        $this->io->success('Dropped database "'.$this->connection->getName().'".');

        return 0;
    }
}
