<?php

namespace Ofernandoavila\TucanoCore\Enum;

enum StubEnum: string
{
    case MIGRATION = 'migration.php';
    case SEED = 'seed.php';
    case MODEL = 'model.php';
    case CONTROLLER = 'controller.php';
}
