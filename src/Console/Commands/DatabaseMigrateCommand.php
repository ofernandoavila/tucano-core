<?php

namespace Ofernandoavila\TucanoCore\Console\Commands;

use Ofernandoavila\TucanoCore\Core\Container;
use Ofernandoavila\TucanoCore\Core\Migration;
use Ofernandoavila\TucanoCore\Util\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseMigrateCommand extends Command
{
    public function __construct(
        private string $rootPath,
        private Container $container
    ) {
        $parent = parent::__construct('database:migrate');

        return $parent;
    }

    protected function configure(): void
    {
        $this->setDescription('Run all migrations');
        $this->addArgument('name', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        if ($name) {
            $output->writeln('Running migration: ' . $name);
            return Command::SUCCESS;
        }

        $this->container
            ->resolve(Migration::class)
            ->createMigrationTable()
            ->migrate();

        return Command::SUCCESS;
    }
}
