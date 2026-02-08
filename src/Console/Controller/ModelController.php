<?php

namespace Ofernandoavila\TucanoCore\Console\Controller;

use Ofernandoavila\TucanoCore\Enum\StubEnum;
use Ofernandoavila\TucanoCore\Util\File;

class ModelController
{
    public static function new(string $name)
    {
        $tableName = strtolower($name) . "s";

        MigrationController::create_file('criar_tabela_' . $tableName);
        ModelController::create_file($name, ['name' => $name, 'table_name' => $tableName]);
        ControllerController::new($name, $tableName);

        return true;
    }

    public static function create_file(string $name, array $replaces = [])
    {
        return File::generate_file($name, StubEnum::MODEL, $replaces);
    }
}
