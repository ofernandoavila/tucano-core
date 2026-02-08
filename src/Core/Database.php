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
    ) {
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
}
