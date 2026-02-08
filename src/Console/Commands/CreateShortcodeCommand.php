<?php

namespace Ofernandoavila\TucanoCore\Console\Commands;

use Ofernandoavila\TucanoCore\Util\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateShortcodeCommand extends Command
{
    public function __construct(
        private string $rootPath
    ) {
        $parent = parent::__construct('create:shortcode');

        return $parent;
    }

    protected function configure(): void
    {
        $this->setDescription('Create a new shortcode');
        $this->addArgument('name', InputArgument::REQUIRED);
        $this->addArgument('shortcode', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $shortcode = $input->getArgument('shortcode');

        if (str_contains($name, 'Shortcode'))
            $name = str_replace('Shortcode', '', $name);

        $replaces = ['name' => $name, 'shortcode' => $shortcode];

        if (!file_exists($this->rootPath . '/app'))
            mkdir($this->rootPath . '/app');

        if (!file_exists($this->rootPath . '/app/Shortcode'))
            mkdir($this->rootPath . '/app/Shortcode');

        File::writeFile(
            $this->rootPath . '/app/Shortcode/' . $name . 'Shortcode.php',
            File::getStubContent('shortcode'),
            $replaces
        );

        $output->writeln('Shortcode ' . $name . 'Shortcode.php created!');

        return Command::SUCCESS;
    }
}
