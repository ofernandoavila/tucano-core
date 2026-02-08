<?php

namespace Ofernandoavila\TucanoCore\Core;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Events\Dispatcher;
use Ofernandoavila\TucanoCore\Core\Container as CoreContainer;

class Database
{
    private $capsule;

    public function __construct(
        private CoreContainer $container
    )
    {
        $config = $this->container->resolve(Config::class);

        $this->capsule = new Capsule();
        $this->configureDatabase($config->getDatabaseConfig());
    }

    private function configureDatabase(array $config)
    {
        $this->capsule->addConnection([
            'driver'    => $config['driver'],
            'host'      => $config['host'],
            'database'  => $config['database'],
            'username'  => $config['user'],
            'password'  => $config['password'],
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => $config['table_prefix']
        ]);

        $this->capsule->setEventDispatcher(
            new Dispatcher(new Container)
        );

        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }

    public static function createMigrationTable() {
        if (!Capsule::schema()->hasTable("migrations")) {
            Capsule::schema()->create("migrations", function (Blueprint $table) {
                $table->string('migration', 500)->primary();
                $table->string('version', 1000)->nullable(false);
            });
        }
    }

    public static function applyMigration(string $migrationName, mixed $migration, string $version = '0.0.0') {
        if(!self::verifyMigration($migrationName)) {
            $migration->Up();
            self::registerMigration($migrationName, $version);
        }
    }

    public static function removeMigration(string $migrationName, mixed $migration) {
        if(self::verifyMigration($migrationName)) {
            $migration->Down();
            self::deleteMigration($migrationName);
        }
    }

    public static function registerMigration(string $migration, string $version) {
        $query = Capsule::table('migrations');
        $query->insert(compact('migration', 'version'));
    }

    public static function deleteMigration(string $migration) {
        $query = Capsule::table('migrations');
        $query->where('migration', $migration)->delete();
    }

    private static function verifyMigration(string $migration) {
        $result = Capsule::table('migrations')->where('migration', $migration)->get();
        
        return sizeof($result) > 0;
    }

    public static function dropMigrationTable() {
        return Capsule::schema()->dropIfExists("migrations");
    }
}
