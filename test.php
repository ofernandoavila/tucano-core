<?php

use App\Http\Controller\Controller;
use Ofernandoavila\TucanoCore\Core\Core;

require_once 'vendor/autoload.php';

Core::install(__DIR__);

// $core = new Core(__DIR__);
// $core
//     ->configure(function($core) {
//         $core->container->register(Controller::class, fn() => new Controller($core->container));
//     })
//     ->init();