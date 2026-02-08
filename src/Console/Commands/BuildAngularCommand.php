<?php

namespace Ofernandoavila\TucanoCore\Console\Commands;

use Illuminate\Console\Command;
use Ofernandoavila\TucanoCore\Console\Controller\AngularController;

class BuildAngularCommand extends Command
{
    protected $signature = 'build:angular';

    protected $description = 'Realiza um build da aplicação angular';

    public function handle()
    {
        foreach (['webcomponents'] as $app) {
            $builder = new AngularController($app);
            $builder->build();
        }
    }
}
