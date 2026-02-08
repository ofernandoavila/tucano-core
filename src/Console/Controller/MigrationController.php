<?php

namespace Ofernandoavila\TucanoCore\Console\Controller;

use Ofernandoavila\TucanoCore\Enum\StubEnum;
use Ofernandoavila\TucanoCore\Util\File;

class MigrationController
{
    public static function create_file(string $migrationName)
    {
        $fileName = date('Y_m_d_His') . '_' . $migrationName;

        if (str_contains($migrationName, 'criar_tabela_'))
            $migrationName = str_replace('criar_tabela_', '', $migrationName);

        $replaces = ['table_name' => $migrationName];

        File::generate_file($fileName, StubEnum::MIGRATION, $replaces);

        return $fileName;
    }
}
