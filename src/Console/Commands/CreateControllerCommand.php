<?php

namespace Ofernandoavila\TucanoCore\Console\Commands;

use Ofernandoavila\TucanoCore\Util\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateControllerCommand extends Command
{
    public function __construct(
        private string $rootPath
    ) {
        $parent = parent::__construct('create:controller');

        return $parent;
    }

    protected function configure(): void
    {
        $this->setDescription('Create a new controller');
        $this->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        if (str_contains($name, 'Controller')) {
            $name = str_replace('Controller', '', $name);
        }

        $replaces = ['name' => $name];

        if (!file_exists($this->rootPath . '/app'))
            mkdir($this->rootPath . '/app');

        if (!file_exists($this->rootPath . '/app/Http'))
            mkdir($this->rootPath . '/app/Http');

        if (!file_exists($this->rootPath . '/app/Http/Controller'))
            mkdir($this->rootPath . '/app/Http/Controller');

        File::writeFile(
            $this->rootPath . '/app/Http/Controller/' . $name . 'Controller.php',
            File::getStubContent('controller'),
            $replaces
        );

        $output->writeln('Controller ' . $name . 'Controller.php created!');

        return Command::SUCCESS;
    }
}
