<?php

namespace Ofernandoavila\TucanoCore\Console\Commands;

use Illuminate\Console\Command;
use Ofernandoavila\TucanoCore\Console\Controller\ModelController;
use Ofernandoavila\TucanoCore\Enum\StubEnum;
use Ofernandoavila\TucanoCore\Util\File;

class ModelCommand extends Command
{
    protected $signature = 'model:new {name}';

    protected $description = 'Cria um novo modelo';

    public function handle()
    {
        ModelController::new($this->argument('name'));

        $this->info("Modelo criado em: {$this->argument('name')}");
    }
}
