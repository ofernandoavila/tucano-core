<?php

namespace Ofernandoavila\TucanoCore\Console\Commands;

use Ofernandoavila\TucanoCore\Util\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateMigrationCommand extends Command
{
    public function __construct(
        private string $rootPath
    ) {
        $parent = parent::__construct('create:migration');

        return $parent;
    }

    protected function configure(): void
    {
        $this->setDescription('Create a new migration');
        $this->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        $fileName = date('Y_m_d_His') . '_' . $name;

        if (str_contains($name, 'create_table_'))
            $name = str_replace('create_table_', '', $name);

        $replaces = ['table_name' => $name];

        if (!file_exists($this->rootPath . '/database'))
            mkdir($this->rootPath . '/database');

        if (!file_exists($this->rootPath . '/database/migrations'))
            mkdir($this->rootPath . '/database/migrations');

        File::writeFile(
            $this->rootPath . '/database/migrations/' . $fileName . '.php',
            File::getStubContent('migration'),
            $replaces
        );

        $output->writeln('Migration ' . $fileName . '.php created!');

        return Command::SUCCESS;
    }
}
