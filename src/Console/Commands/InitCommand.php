<?php

namespace Ofernandoavila\TucanoCore\Console\Commands;

use Ofernandoavila\TucanoCore\Util\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class InitCommand extends Command
{
    private $folders = [
        'app',
        'app/Shortcode',
        'app/Http',
        'app/Http/Controller',
        'app/View',
        'assets',
        'assets/js',
        'assets/css',
        'database',
        'database/migrations',
        'database/seeds',
    ];

    public function __construct(
        private string $rootPath
    ) {
        $parent = parent::__construct('init');

        return $parent;
    }

    protected function configure(): void
    {
        $this->setDescription('Create a config.php file for your plugin');
        $this->addArgument('name', InputArgument::REQUIRED, 'Plugin name');
        $this->addArgument('description', InputArgument::OPTIONAL, 'Plugin description');
        $this->addArgument('author', InputArgument::OPTIONAL, 'Plugin author');
        $this->addArgument('version', InputArgument::OPTIONAL, 'Plugin version');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = new QuestionHelper();

        $name = $input->getArgument('name');
        $description = $input->getArgument('description');
        $author = $input->getArgument('author');
        $version = $input->getArgument('version');

        if (!$description) {
            $question = new Question('Description: ');
            $description = $helper->ask($input, $output, $question) ?? "Edit config.php and plugin.php to change the description.";
        }

        if (!$author) {
            $question = new Question('Author: ');
            $author = $helper->ask($input, $output, $question) ?? "Unknow";
        }

        if (!$version) {
            $question = new Question('Version: ');
            $version = $helper->ask($input, $output, $question) ?? "1.0.0";
        }

        $output->writeln('');

        $app = compact('name', 'description', 'author', 'version');

        File::writeFile(
            $this->rootPath . '/config.php',
            File::getStubContent('config'),
            $app
        );

        $output->writeln('Config file created!');

        File::writeFile(
            $this->rootPath . '/plugin.php',
            File::getStubContent('plugin'),
            $app
        );
        $output->writeln('Plugin file created!');

        foreach ($this->folders as $folder) {
            if (!file_exists($this->rootPath . '/' . $folder)) {
                mkdir($this->rootPath . '/' . $folder);
            }
        }
        $output->writeln('Project folders created!');

        shell_exec("cp " . $this->rootPath . "/.env.example " . $this->rootPath . "/.env");

        shell_exec("cp " . __DIR__ . "/../stubs/components " . $this->rootPath . " -R");

        $output->writeln('Installing dependencies...');
        $output->writeln('');
        shell_exec("cd " . $this->rootPath . "/components && npm install");
        $output->writeln('');
        $output->writeln('Dependencies installed!');
        $output->writeln('');

        $build = new BuildAngularCommand($this->rootPath);

        $build->execute($input, $output);

        return Command::SUCCESS;
    }
}
