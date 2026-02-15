<?php

namespace Ofernandoavila\TucanoCore\Core;

class Core
{
    public Container $container;
    private Factory $factory;
    private string $rootPath;

    public function __construct(string $rootPath) {
        $this->rootPath = $rootPath;
        $config = new Config($this->rootPath);

        $this->container = new Container();
        $this->container->register(Config::class, fn() => $config);        
        $this->factory = new Factory($this->container);
    }

    public function configure(mixed $callback) {
        $callback($this);

        return $this;
    }

    public function init()
    {
        return $this->factory->init();
    }

    public static function install(string $rootPath)
    {
        return (new Core($rootPath))->container->resolve(Factory::class)->install();
    }

    public static function uninstall(string $rootPath)
    {
        return (new Core($rootPath))->container->resolve(Factory::class)->uninstall();
    }

    public function     getRootPath() {
        return $this->rootPath;
    }
}
