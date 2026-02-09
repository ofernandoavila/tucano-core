<?php

namespace Ofernandoavila\TucanoCore\Console\Commands;

use Ofernandoavila\TucanoCore\Util\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateEntityCommand extends Command
{
    public function __construct(
        private string $rootPath
    ) {
        $parent = parent::__construct('create:entity');

        return $parent;
    }

    protected function configure(): void
    {
        $this->setDescription('Create a new entity');
        $this->addArgument('name', InputArgument::REQUIRED);
        $this->addArgument('tableName', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $tableName = $input->getArgument('tableName');

        $migration = new CreateMigrationCommand($this->rootPath);
        $migration->execute($input, $output);

        $model = new CreateModelCommand($this->rootPath);
        $model->execute($input, $output);

        $controller = new CreateControllerCommand($this->rootPath);
        $controller->execute($input, $output);

        return Command::SUCCESS;
    }
}
