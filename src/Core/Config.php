<?php

namespace Ofernandoavila\TucanoCore\Core;

final class Config {
    public function __construct(
        private string $rootPath
    ) {}

    public function getControllerDirPath() {
        return $this->rootPath . '/app/Http/Controller';
    }

    public function getShortcodesDirPath() {
        return $this->rootPath . '/app/Shortcode';
    }

    public function getRoutesPath() {
        return $this->rootPath . '/config/routes.php';
    }

    public function getMigrationsPath() {
        return $this->rootPath . '/database/migrations';
    }

    public function getSeedsPath() {
        return $this->rootPath . '/database/seeds';
    }

    public function getDatabaseConfig() {
        return [
            'table_prefix' => '',
            'driver'    => 'mysql',
            'host'      => '',
            'database'  => '',
            'user'  => '',
            'password'  => ''
        ];
    }
}