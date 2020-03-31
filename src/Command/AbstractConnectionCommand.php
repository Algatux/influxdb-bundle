<?php

declare(strict_types=1);

namespace Yproximite\InfluxDbBundle\Command;

use InfluxDB\Database;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class for commands which needs the connection option.
 */
abstract class AbstractConnectionCommand extends AbstractCommand
{
    /**
     * @var Database
     */
    protected $connection;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->addOption('connection', null, InputOption::VALUE_OPTIONAL, 'The connection to use for this command')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->connection = $input->getOption('connection')
            ? $this->getContainer()->get('yproximite_influx_db.connection.'.$input->getOption('connection').'.http')
            : $this->getContainer()->get('yproximite_influx_db.connection.http')
        ;
    }
}
