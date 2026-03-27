<?php

namespace Ofernandoavila\TucanoCore\Console;

use Ofernandoavila\TucanoCore\Console\Commands\BuildAngularCommand;
use Ofernandoavila\TucanoCore\Console\Commands\CreateControllerCommand;
use Ofernandoavila\TucanoCore\Console\Commands\CreateEntityCommand;
use Ofernandoavila\TucanoCore\Console\Commands\CreateMigrationCommand;
use Ofernandoavila\TucanoCore\Console\Commands\CreateModelCommand;
use Ofernandoavila\TucanoCore\Console\Commands\CreateSeedCommand;
use Ofernandoavila\TucanoCore\Console\Commands\CreateShortcodeCommand;
use Ofernandoavila\TucanoCore\Console\Commands\DatabaseMigrateCommand;
use Ofernandoavila\TucanoCore\Console\Commands\DatabaseRollbackCommand;
use Ofernandoavila\TucanoCore\Console\Commands\DatabaseSeedCommand;
use Ofernandoavila\TucanoCore\Console\Commands\InitCommand;
use Ofernandoavila\TucanoCore\Core\Config;
use Ofernandoavila\TucanoCore\Core\Container;
use Ofernandoavila\TucanoCore\Core\Factory;
use Symfony\Component\Console\Application;

class Console extends Application
{
    public function __construct(
        private string $rootPath
    ) {
        parent::__construct('Tucano CLI', '1.0.0');

        $container = new Container();
        $config = new Config($this->rootPath);

        $container->register(Config::class, fn() => $config);
        new Factory($container);

        $this->add(new InitCommand($this->rootPath, $container));
        $this->add(new BuildAngularCommand($this->rootPath, $container));
        $this->add(new CreateMigrationCommand($this->rootPath, $container));
        $this->add(new CreateShortcodeCommand($this->rootPath, $container));
        $this->add(new CreateSeedCommand($this->rootPath, $container));
        $this->add(new CreateModelCommand($this->rootPath, $container));
        $this->add(new CreateControllerCommand($this->rootPath, $container));
        $this->add(new CreateEntityCommand($this->rootPath, $container));
        $this->add(new DatabaseSeedCommand($this->rootPath, $container));
        $this->add(new DatabaseMigrateCommand($this->rootPath, $container));
        $this->add(new DatabaseRollbackCommand($this->rootPath, $container));
    }
}
