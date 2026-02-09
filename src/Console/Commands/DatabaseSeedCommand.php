<?php

namespace Ofernandoavila\TucanoCore\Console\Commands;

use Ofernandoavila\TucanoCore\Core\Container;
use Ofernandoavila\TucanoCore\Core\Migration;
use Ofernandoavila\TucanoCore\Util\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseSeedCommand extends Command
{
    public function __construct(
        private string $rootPath,
        private Container $container
    ) {
        $parent = parent::__construct('database:seed');

        return $parent;
    }

    protected function configure(): void
    {
        $this->setDescription('Run all seeds');
        $this->addArgument('name', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $seeds = File::listDir($this->rootPath . '/database/seeds');

        if ($input->getArgument('name')) {
            if (sizeof(array_filter($seeds, fn($item) => is_int(strpos($item, $input->getArgument('name'))))) > 0) {
                $seed = [];

                $result = array_filter($seeds, fn($item) => is_int(strpos($item, $input->getArgument('name'))));
                $seed = $result[array_keys($result)[0]];

                $seeds = [];
                $seeds[] = $seed;
            }
        }

        foreach ($seeds as $seed) {
            $class = require $seed;
            $class->seed();
        }

        return Command::SUCCESS;
    }
}
