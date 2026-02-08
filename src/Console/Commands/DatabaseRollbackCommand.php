<?php

namespace Ofernandoavila\TucanoCore\Console\Commands;

use Ofernandoavila\TucanoCore\Core\Container;
use Ofernandoavila\TucanoCore\Core\Migration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseRollbackCommand extends Command
{
    public function __construct(
        private string $rootPath,
        private Container $container
    ) {
        $parent = parent::__construct('database:rollback');

        return $parent;
    }

    protected function configure(): void
    {
        $this->setDescription('Rollback migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->container
            ->resolve(Migration::class)
            ->drop();

        return Command::SUCCESS;
    }
}
