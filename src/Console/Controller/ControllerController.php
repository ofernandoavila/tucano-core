<?php

namespace Ofernandoavila\TucanoCore\Console\Controller;

use Ofernandoavila\TucanoCore\Enum\StubEnum;
use Ofernandoavila\TucanoCore\Util\File;

class ControllerController
{
    public static function new(string $name, string $tableName)
    {
        ControllerController::create_file($name, ['name' => $name, 'table_name' => $tableName]);

        return true;
    }

    public static function create_file(string $name, array $replaces = [])
    {
        return File::generate_file($name . 'Controller', StubEnum::CONTROLLER, $replaces);
    }
}
