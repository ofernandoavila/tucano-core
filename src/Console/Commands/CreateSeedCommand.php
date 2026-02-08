<?php

namespace Ofernandoavila\TucanoCore\Console\Commands;

use Ofernandoavila\TucanoCore\Util\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSeedCommand extends Command
{
    public function __construct(
        private string $rootPath
    ) {
        $parent = parent::__construct('create:seed');

        return $parent;
    }

    protected function configure(): void
    {
        $this->setDescription('Create a new seed');
        $this->addArgument('model', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('model');

        $fileName = date('Y_m_d_His') . '_' . $name;

        $replaces = ['name' => $name];

        if (!file_exists($this->rootPath . '/database'))
            mkdir($this->rootPath . '/database');

        if (!file_exists($this->rootPath . '/database/seeds'))
            mkdir($this->rootPath . '/database/seeds');

        File::writeFile(
            $this->rootPath . '/database/seeds/' . $fileName . '.php',
            File::getStubContent('seed'),
            $replaces
        );

        $output->writeln('Seed ' . $fileName . '.php created!');

        return Command::SUCCESS;
    }
}
