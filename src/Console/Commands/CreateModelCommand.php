<?php

namespace Ofernandoavila\TucanoCore\Console\Commands;

use Ofernandoavila\TucanoCore\Util\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateModelCommand extends Command
{
    public function __construct(
        private string $rootPath
    ) {
        $parent = parent::__construct('create:model');

        return $parent;
    }

    protected function configure(): void
    {
        $this->setDescription('Create a new model');
        $this->addArgument('name', InputArgument::REQUIRED);
        $this->addArgument('table_name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $tableName = $input->getArgument('table_name');

        $replaces = ['name' => $name, 'table_name' => $tableName];

        if (!file_exists($this->rootPath . '/app'))
            mkdir($this->rootPath . '/app');

        if (!file_exists($this->rootPath . '/app/Model'))
            mkdir($this->rootPath . '/app/Model');

        File::writeFile(
            $this->rootPath . '/app/Model/' . $name . '.php',
            File::getStubContent('model'),
            $replaces
        );

        $output->writeln('Model ' . $name . '.php created!');

        return Command::SUCCESS;
    }
}
