<?php

namespace Ofernandoavila\TucanoCore\Console\Commands;

use Illuminate\Console\Command;
use Ofernandoavila\TucanoCore\Enum\StubEnum;
use Ofernandoavila\TucanoCore\Util\File;

class MigrationNewCommand extends Command
{
    protected $signature = 'migration:new {name}';

    protected $description = 'Cria uma nova migration';

    public function handle()
    {
        $path = $this->get_template($this->argument('name'));

        $this->info("Migração criada em: {$path}");
    }

    private function get_template(string $name)
    {
        $fileName = date('Y_m_d_His') . '_' . $name;

        if (str_contains($name, 'criar_tabela_'))
            $name = str_replace('criar_tabela_', '', $name);

        $replaces = ['table_name' => $name];

        File::generate_file($fileName, StubEnum::MIGRATION, $replaces);

        return $fileName;
    }
}
