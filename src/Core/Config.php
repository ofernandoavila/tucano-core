<?php

namespace Ofernandoavila\TucanoCore\Core;

final class Config
{
    private array $config;

    public function __construct(
        private string $rootPath
    ) {
        $this->config = $this->loadConfigFile();
    }

    private function loadConfigFile()
    {
        if (!file_exists($this->rootPath . '/config.php')) {
            return require __DIR__ . '/../Console/stubs/config.stub.php';
        }

        return require $this->rootPath . '/config.php';
    }

    public function getControllerDirPath()
    {
        return $this->rootPath . '/app/Http/Controller';
    }

    public function getShortcodesDirPath()
    {
        return $this->rootPath . '/app/Shortcode';
    }

    public function getRoutesPath()
    {
        return $this->rootPath . '/routes.php';
    }

    public function getMigrationsPath()
    {
        return $this->rootPath . '/database/migrations';
    }

    public function getSeedsPath()
    {
        return $this->rootPath . '/database/seeds';
    }

    public function getDatabaseConfig()
    {
        if (!isset($this->config['database']))
            return null;

        return $this->config['database'];
    }

    public function getWebComponentsConfig()
    {
        if (!isset($this->config['webcomponents']))
            return null;

        return $this->config['webcomponents'];
    }
}
