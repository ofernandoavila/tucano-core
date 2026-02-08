<?php

namespace Ofernandoavila\TucanoCore\Core;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class Migration
{
    private Config $config;

    public function __construct(
        private Container $container
    ) {
        $this->config = $this->container->resolve(Config::class);
    }

    public function migrate()
    {
        if (file_exists($this->config->getMigrationsPath())) {
            $migrations = array_filter(scandir($this->config->getMigrationsPath()), fn($item) => $item != '.' && $item != '..');

            foreach ($migrations as $migration) {
                $migrationName = explode('.', $migration)[0];
                $migration = require $this->config->getMigrationsPath() . '/' . $migration;

                self::apply($migrationName, $migration);
            }
        }

        return $this;
    }

    public function drop()
    {
        if (file_exists($this->config->getMigrationsPath())) {
            $migrations = array_filter(scandir($this->config->getMigrationsPath()), fn($item) => $item != '.' && $item != '..');

            foreach (array_reverse($migrations) as $migration) {
                $migrationName = explode('.', $migration)[0];
                $migration = require $this->config->getMigrationsPath() . '/' . $migration;

                self::remove($migrationName, $migration);
            }
        }
    }

    private function apply(string $migration, mixed $migrationFile, string $version = '0.0.0')
    {
        if (!self::verify($migration)) {
            $migrationFile->Up();

            $query = Capsule::table('migrations');
            $query->insert(compact('migration', 'version'));
        }
    }

    private function remove(string $migrationName, mixed $migration)
    {
        if (self::verify($migrationName)) {
            $migration->Down();
            $query = Capsule::table('migrations');
            $query->where('migration', $migrationName)->delete();
        }
    }

    private function verify(string $migration)
    {
        $result = Capsule::table('migrations')->where('migration', $migration)->get();

        return sizeof($result) > 0;
    }

    public function createMigrationTable()
    {
        $migration = self::migrationRoot();
        $migration->Up();

        return $this;
    }

    public function dropMigrationTable()
    {
        $migration = self::migrationRoot();
        $migration->Down();

        return $this;
    }

    private static function migrationRoot()
    {
        return new class
        {
            public function Up()
            {
                if (!Capsule::schema()->hasTable("migrations")) {
                    Capsule::schema()->create("migrations", function (Blueprint $table) {
                        $table->string('migration', 500)->primary();
                        $table->string('version', 1000)->nullable(false);
                    });
                }
            }

            public function Down()
            {
                return Capsule::schema()->dropIfExists("migrations");
            }
        };
    }
}
